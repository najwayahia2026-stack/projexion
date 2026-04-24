# SmartGrad AI Similarity Service

Python FastAPI microservice for checking project and file similarity using NLP techniques, and detecting AI-generated content.

## Features

- **Project Similarity Checking**: Compare new projects with existing projects
- **File Similarity Checking**: Compare uploaded files with existing files
- **AI Content Detection**: Detect if text is AI-generated
- **PDF Text Extraction**: Extract text from PDF files

## Requirements

- Python 3.8 or higher
- pip (Python package manager)

## Installation

### Windows

1. Open Command Prompt or PowerShell
2. Navigate to the `python_ai_service` directory
3. Run the installation script:
   ```bash
   start_ai_service.bat
   ```

Or manually:
```bash
cd python_ai_service
pip install -r requirements.txt
```

### Linux/Mac

1. Open Terminal
2. Navigate to the `python_ai_service` directory
3. Make the script executable and run:
   ```bash
   chmod +x start_ai_service.sh
   ./start_ai_service.sh
   ```

Or manually:
```bash
cd python_ai_service
pip3 install -r requirements.txt
python3 main.py
```

## Running the Service

### Using the Script

**Windows:**
```bash
start_ai_service.bat
```

**Linux/Mac:**
```bash
./start_ai_service.sh
```

### Manual Start

```bash
python main.py
```

Or using uvicorn directly:
```bash
uvicorn main:app --host 0.0.0.0 --port 8001 --reload
```

## API Endpoints

### GET `/health`

Health check endpoint.

**Response:**
```json
{
  "status": "healthy",
  "service": "SmartGrad AI Similarity Service",
  "version": "2.0.0"
}
```

### POST `/api/check-similarity`

Check similarity of a project with existing projects.

**Request Body:**
```json
{
  "project": {
    "title": "Project Title",
    "description": "Project Description",
    "objectives": "Project Objectives"
  },
  "existing_projects": [
    {
      "id": 1,
      "title": "Existing Project",
      "description": "Description",
      "objectives": "Objectives"
    }
  ]
}
```

**Response:**
```json
{
  "similarity_percentage": 75.5,
  "similar_projects": [
    {
      "id": 1,
      "title": "Similar Project",
      "similarity": 75.5
    }
  ],
  "details": "Compared with 10 existing projects. Found 3 similar projects."
}
```

### POST `/api/check-file-similarity`

Check similarity of a file text with existing files.

**Request Body:**
```json
{
  "text": "File content text...",
  "existing_files": [
    {
      "id": 1,
      "text": "Existing file content...",
      "file_name": "existing_file.pdf"
    }
  ]
}
```

**Response:**
```json
{
  "similarity_percentage": 65.2,
  "similar_files": [
    {
      "id": 1,
      "file_name": "similar_file.pdf",
      "similarity": 65.2
    }
  ],
  "ai_probability": 45.5,
  "details": "Compared with 10 existing files. Found 2 similar files."
}
```

### POST `/api/detect-ai`

Detect if text is AI-generated.

**Request Body:**
```json
{
  "text": "Text content to analyze..."
}
```

**Response:**
```json
{
  "ai_probability": 65.5,
  "is_ai_generated": true,
  "confidence": "medium",
  "details": "Analysis based on 50 sentences and 1200 words. Detected patterns suggest AI generation with medium confidence."
}
```

### POST `/api/extract-pdf-text`

Extract text from PDF file.

**Request Body:**
```json
{
  "file_path": "/path/to/file.pdf"
}
```

**Response:**
```json
{
  "text": "Extracted text from PDF...",
  "success": true,
  "message": "Text extracted successfully"
}
```

## Configuration

The service runs on `http://localhost:8001` by default. To change the port, edit `main.py`:

```python
uvicorn.run(app, host="0.0.0.0", port=8001)
```

## Technology Stack

- **FastAPI**: Modern Python web framework
- **scikit-learn**: Machine learning library for TF-IDF and Cosine Similarity
- **TF-IDF Vectorization**: Text feature extraction
- **Cosine Similarity**: Similarity measurement
- **PyPDF2/pdfplumber**: PDF text extraction

## Troubleshooting

### Port Already in Use

If port 8001 is already in use, either:
1. Stop the other service using that port
2. Change the port in `main.py`

### Dependencies Installation Issues

If you encounter issues installing dependencies:
```bash
pip install --upgrade pip
pip install -r requirements.txt --force-reinstall
```

### PDF Extraction Not Working

Make sure PyPDF2 or pdfplumber is installed:
```bash
pip install PyPDF2 pdfplumber
```

## Logs

The service logs all requests and errors to the console. Check the terminal output for debugging information.

## Support

For issues or questions, check the main project documentation or contact the development team.
