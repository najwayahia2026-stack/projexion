"""
SmartGrad AI Similarity Service
FastAPI service for checking project and file similarity using NLP
"""

from fastapi import FastAPI, HTTPException
from fastapi.middleware.cors import CORSMiddleware
from pydantic import BaseModel
from typing import List, Optional
from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.metrics.pairwise import cosine_similarity
import numpy as np
import logging

# Configure logging
logging.basicConfig(
    level=logging.INFO,
    format='%(asctime)s - %(name)s - %(levelname)s - %(message)s'
)
logger = logging.getLogger(__name__)

app = FastAPI(
    title="SmartGrad AI Similarity Service",
    description="AI service for similarity checking and AI content detection",
    version="2.0.0"
)

# Add CORS middleware
app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],  # In production, specify allowed origins
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

# ==================== Project Similarity Models ====================

class ProjectData(BaseModel):
    id: Optional[int] = None
    title: str
    description: str
    objectives: str

class SimilarityRequest(BaseModel):
    project: ProjectData
    existing_projects: List[ProjectData]

class SimilarProject(BaseModel):
    id: int
    title: str
    similarity: float

class SimilarityResponse(BaseModel):
    similarity_percentage: float
    similar_projects: List[SimilarProject]
    details: Optional[str] = None

# ==================== File Similarity Models ====================

class FileData(BaseModel):
    id: int
    text: str
    file_name: str

class FileSimilarityRequest(BaseModel):
    text: str
    existing_files: List[FileData]

class SimilarFile(BaseModel):
    id: int
    file_name: str
    similarity: float

class FileSimilarityResponse(BaseModel):
    similarity_percentage: float
    similar_files: List[SimilarFile]
    ai_probability: Optional[float] = None
    details: Optional[str] = None

# ==================== AI Detection Models ====================

class AIDetectionRequest(BaseModel):
    text: str

class AIDetectionResponse(BaseModel):
    ai_probability: float
    is_ai_generated: bool
    confidence: str
    details: Optional[str] = None

# ==================== PDF Extraction Models ====================

class PDFExtractionRequest(BaseModel):
    file_path: str

class PDFExtractionResponse(BaseModel):
    text: str
    success: bool
    message: Optional[str] = None

# ==================== Helper Functions ====================

def calculate_similarity(new_project: ProjectData, existing_projects: List[ProjectData]) -> SimilarityResponse:
    """
    Calculate similarity between a new project and existing projects using TF-IDF and Cosine Similarity
    """
    try:
        # Combine title, description, and objectives for each project
        new_text = f"{new_project.title} {new_project.description} {new_project.objectives}"
        existing_texts = [
            f"{p.title} {p.description} {p.objectives}" 
            for p in existing_projects
        ]
        
        if not existing_texts:
            return SimilarityResponse(
                similarity_percentage=0.0,
                similar_projects=[],
                details="No existing projects to compare with"
            )
        
        # Create TF-IDF vectorizer
        vectorizer = TfidfVectorizer(
            max_features=1000,
            stop_words='english',
            ngram_range=(1, 2)
        )
        
        # Fit and transform
        all_texts = [new_text] + existing_texts
        tfidf_matrix = vectorizer.fit_transform(all_texts)
        
        # Calculate cosine similarity
        similarities = cosine_similarity(tfidf_matrix[0:1], tfidf_matrix[1:]).flatten()
        
        # Get maximum similarity
        max_similarity = float(np.max(similarities)) * 100
        
        # Find similar projects (similarity > 30%)
        similar_projects = []
        for idx, similarity in enumerate(similarities):
            similarity_percent = float(similarity) * 100
            if similarity_percent >= 30:
                similar_projects.append(SimilarProject(
                    id=existing_projects[idx].id,
                    title=existing_projects[idx].title,
                    similarity=similarity_percent
                ))
        
        # Sort by similarity (descending)
        similar_projects.sort(key=lambda x: x.similarity, reverse=True)
        
        # Generate details
        details = f"Compared with {len(existing_projects)} existing projects. "
        if similar_projects:
            details += f"Found {len(similar_projects)} similar projects."
        else:
            details += "No significant similarity found."
        
        return SimilarityResponse(
            similarity_percentage=max_similarity,
            similar_projects=similar_projects[:5],  # Top 5 most similar
            details=details
        )
    except Exception as e:
        logger.error(f"Error calculating similarity: {str(e)}")
        raise

def calculate_file_similarity(new_text: str, existing_files: List[FileData]) -> FileSimilarityResponse:
    """
    Calculate similarity between a new file text and existing files using TF-IDF and Cosine Similarity
    Also detect AI-generated content
    """
    try:
        # Detect AI content first
        ai_detection = detect_ai_content(new_text)
        
        if not existing_files:
            return FileSimilarityResponse(
                similarity_percentage=0.0,
                similar_files=[],
                ai_probability=ai_detection.ai_probability,
                details=f"No existing files to compare with. {ai_detection.details}"
            )
        
        existing_texts = [f.file_name + " " + f.text for f in existing_files]
        
        # Create TF-IDF vectorizer with Arabic support
        vectorizer = TfidfVectorizer(
            max_features=2000,
            stop_words='english',  # Can be extended to support Arabic
            ngram_range=(1, 3),  # Use 1-3 grams for better text matching
            min_df=1,
            max_df=0.95
        )
        
        # Fit and transform
        all_texts = [new_text] + existing_texts
        tfidf_matrix = vectorizer.fit_transform(all_texts)
        
        # Calculate cosine similarity
        similarities = cosine_similarity(tfidf_matrix[0:1], tfidf_matrix[1:]).flatten()
        
        # Get maximum similarity
        max_similarity = float(np.max(similarities)) * 100
        
        # Find similar files (similarity > 30%)
        similar_files = []
        for idx, similarity in enumerate(similarities):
            similarity_percent = float(similarity) * 100
            if similarity_percent >= 30:
                similar_files.append(SimilarFile(
                    id=existing_files[idx].id,
                    file_name=existing_files[idx].file_name,
                    similarity=similarity_percent
                ))
        
        # Sort by similarity (descending)
        similar_files.sort(key=lambda x: x.similarity, reverse=True)
        
        # Generate details
        details = f"Compared with {len(existing_files)} existing files. "
        if similar_files:
            details += f"Found {len(similar_files)} similar files. "
        else:
            details += "No significant similarity found. "
        details += ai_detection.details
        
        return FileSimilarityResponse(
            similarity_percentage=max_similarity,
            similar_files=similar_files[:10],  # Top 10 most similar
            ai_probability=ai_detection.ai_probability,
            details=details
        )
    except Exception as e:
        logger.error(f"Error calculating file similarity: {str(e)}")
        raise

def detect_ai_content(text: str) -> AIDetectionResponse:
    """
    Detect if text is AI-generated using various heuristics
    """
    try:
        if not text or len(text.strip()) < 50:
            return AIDetectionResponse(
                ai_probability=0.0,
                is_ai_generated=False,
                confidence="low",
                details="Text is too short for reliable detection"
            )
        
        text_lower = text.lower()
        ai_probability = 0.0
        
        # Heuristic 1: Check for common AI patterns
        ai_patterns = [
            'furthermore', 'moreover', 'additionally', 'consequently',
            'nevertheless', 'nonetheless', 'in conclusion', 'to summarize',
            'it is important to note', 'it should be noted', 'it is worth mentioning'
        ]
        pattern_count = sum(1 for pattern in ai_patterns if pattern in text_lower)
        if pattern_count > 3:
            ai_probability += 0.15
        
        # Heuristic 2: Check sentence length variation
        sentences = [s for s in text.split('.') if len(s.strip()) > 0]
        if len(sentences) > 5:
            sentence_lengths = [len(s.split()) for s in sentences]
            if len(sentence_lengths) > 5:
                avg_length = sum(sentence_lengths) / len(sentence_lengths)
                variance = sum((x - avg_length) ** 2 for x in sentence_lengths) / len(sentence_lengths)
                std_dev = variance ** 0.5
                # Low variation suggests AI
                if std_dev < avg_length * 0.3:
                    ai_probability += 0.20
        
        # Heuristic 3: Check for overly formal language
        formal_words = ['therefore', 'hence', 'thus', 'accordingly', 'consequently']
        formal_count = sum(1 for word in formal_words if word in text_lower)
        if len(sentences) > 0 and formal_count > len(sentences) * 0.1:
            ai_probability += 0.15
        
        # Heuristic 4: Check for repetition patterns
        words = text_lower.split()
        if len(words) > 100:
            word_freq = {}
            for word in words:
                if len(word) > 4:  # Only check longer words
                    word_freq[word] = word_freq.get(word, 0) + 1
            if word_freq:
                max_freq = max(word_freq.values())
                if max_freq > len(words) * 0.05:
                    ai_probability += 0.10
        
        # Heuristic 5: Check for lack of personal pronouns
        personal_pronouns = ['i', 'we', 'my', 'our', 'me', 'us']
        pronoun_count = sum(1 for word in words if word in personal_pronouns)
        if len(words) > 100 and pronoun_count < len(words) * 0.01:
            ai_probability += 0.15
        
        # Heuristic 6: Check for structure
        paragraphs = [p for p in text.split('\n\n') if len(p.strip()) > 0]
        if len(paragraphs) > 3:
            paragraph_lengths = [len(p.split()) for p in paragraphs]
            if len(paragraph_lengths) > 3:
                avg_para_length = sum(paragraph_lengths) / len(paragraph_lengths)
                para_variance = sum((x - avg_para_length) ** 2 for x in paragraph_lengths) / len(paragraph_lengths)
                para_std_dev = para_variance ** 0.5
                if para_std_dev < avg_para_length * 0.25:
                    ai_probability += 0.15
        
        # Normalize to 0-100%
        ai_probability = min(ai_probability * 100, 100)
        
        # Determine if AI-generated (threshold: 40%)
        is_ai_generated = ai_probability >= 40.0
        
        # Determine confidence level
        if ai_probability < 30:
            confidence = "low"
        elif ai_probability < 60:
            confidence = "medium"
        else:
            confidence = "high"
        
        details = f"Analysis based on {len(sentences)} sentences and {len(words)} words. "
        if is_ai_generated:
            details += f"Detected patterns suggest AI generation with {confidence} confidence."
        else:
            details += f"Text appears to be human-written with {confidence} confidence."
        
        return AIDetectionResponse(
            ai_probability=round(ai_probability, 2),
            is_ai_generated=is_ai_generated,
            confidence=confidence,
            details=details
        )
    except Exception as e:
        logger.error(f"Error detecting AI content: {str(e)}")
        return AIDetectionResponse(
            ai_probability=0.0,
            is_ai_generated=False,
            confidence="low",
            details=f"Error during detection: {str(e)}"
        )

def extract_pdf_text(file_path: str) -> str:
    """
    Extract text from PDF file
    """
    try:
        import PyPDF2
        import io
        
        with open(file_path, 'rb') as file:
            pdf_reader = PyPDF2.PdfReader(file)
            text = ""
            for page_num, page in enumerate(pdf_reader.pages):
                try:
                    text += page.extract_text() + "\n"
                except Exception as e:
                    logger.warning(f"Error extracting text from page {page_num}: {str(e)}")
                    continue
        
        return text.strip()
    except ImportError:
        logger.warning("PyPDF2 not available, trying alternative method")
        try:
            # Try using pdfplumber as alternative
            import pdfplumber
            text = ""
            with pdfplumber.open(file_path) as pdf:
                for page in pdf.pages:
                    page_text = page.extract_text()
                    if page_text:
                        text += page_text + "\n"
            return text.strip()
        except ImportError:
            logger.error("No PDF extraction library available")
            return ""
    except Exception as e:
        logger.error(f"Error extracting PDF text: {str(e)}")
        return ""

# ==================== API Endpoints ====================

@app.get("/")
async def root():
    """
    Root endpoint
    """
    return {
        "service": "SmartGrad AI Similarity Service",
        "version": "2.0.0",
        "status": "running",
        "endpoints": {
            "health": "/health",
            "check-similarity": "/api/check-similarity",
            "check-file-similarity": "/api/check-file-similarity",
            "detect-ai": "/api/detect-ai",
            "extract-pdf-text": "/api/extract-pdf-text"
        }
    }

@app.get("/health")
async def health_check():
    """
    Health check endpoint
    """
    return {
        "status": "healthy",
        "service": "SmartGrad AI Similarity Service",
        "version": "2.0.0"
    }

@app.post("/api/check-similarity", response_model=SimilarityResponse)
async def check_similarity(request: SimilarityRequest):
    """
    Check similarity of a project with existing projects
    """
    try:
        logger.info(f"Checking similarity for project: {request.project.title}")
        result = calculate_similarity(request.project, request.existing_projects)
        logger.info(f"Similarity check completed: {result.similarity_percentage}%")
        return result
    except Exception as e:
        logger.error(f"Error in check_similarity: {str(e)}")
        raise HTTPException(status_code=500, detail=str(e))

@app.post("/api/check-file-similarity", response_model=FileSimilarityResponse)
async def check_file_similarity(request: FileSimilarityRequest):
    """
    Check similarity of a file text with existing files
    """
    try:
        logger.info(f"Checking file similarity for text length: {len(request.text)}")
        logger.info(f"Comparing with {len(request.existing_files)} existing files")
        result = calculate_file_similarity(request.text, request.existing_files)
        logger.info(f"File similarity check completed: {result.similarity_percentage}%")
        return result
    except Exception as e:
        logger.error(f"Error in check_file_similarity: {str(e)}")
        raise HTTPException(status_code=500, detail=str(e))

@app.post("/api/detect-ai", response_model=AIDetectionResponse)
async def detect_ai_endpoint(request: AIDetectionRequest):
    """
    Detect if text is AI-generated
    """
    try:
        logger.info(f"Detecting AI content for text length: {len(request.text)}")
        result = detect_ai_content(request.text)
        logger.info(f"AI detection completed: {result.ai_probability}%")
        return result
    except Exception as e:
        logger.error(f"Error in detect_ai: {str(e)}")
        raise HTTPException(status_code=500, detail=str(e))

@app.post("/api/extract-pdf-text", response_model=PDFExtractionResponse)
async def extract_pdf_text_endpoint(request: PDFExtractionRequest):
    """
    Extract text from PDF file
    """
    try:
        logger.info(f"Extracting text from PDF: {request.file_path}")
        text = extract_pdf_text(request.file_path)
        
        if text:
            return PDFExtractionResponse(
                text=text,
                success=True,
                message="Text extracted successfully"
            )
        else:
            return PDFExtractionResponse(
                text="",
                success=False,
                message="Failed to extract text from PDF. Make sure PyPDF2 or pdfplumber is installed."
            )
    except Exception as e:
        logger.error(f"Error extracting PDF text: {str(e)}")
        raise HTTPException(status_code=500, detail=str(e))

if __name__ == "__main__":
    import uvicorn
    logger.info("Starting SmartGrad AI Similarity Service on port 8001...")
    uvicorn.run(app, host="0.0.0.0", port=8001, log_level="info")
