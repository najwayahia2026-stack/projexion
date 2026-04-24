#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
Test script for SmartGrad AI Similarity Service
Run this script to test if the service is working correctly
"""

import requests
import json
import sys
import io
import os

# Fix encoding for Windows console
if sys.platform == 'win32':
    sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')
    sys.stderr = io.TextIOWrapper(sys.stderr.buffer, encoding='utf-8')

SERVICE_URL = "http://localhost:8001"

def test_health():
    """Test health endpoint"""
    print("Testing /health endpoint...")
    try:
        response = requests.get(f"{SERVICE_URL}/health", timeout=5)
        if response.status_code == 200:
            data = response.json()
            print(f"[OK] Health check passed: {data}")
            return True
        else:
            print(f"[FAIL] Health check failed with status {response.status_code}")
            return False
    except requests.exceptions.ConnectionError:
        print("[FAIL] Cannot connect to service. Make sure it's running on http://localhost:8001")
        return False
    except Exception as e:
        print(f"[FAIL] Error: {str(e)}")
        return False

def test_detect_ai():
    """Test AI detection endpoint"""
    print("\nTesting /api/detect-ai endpoint...")
    try:
        test_text = """
        This is a sample text that we are going to analyze. 
        It contains multiple sentences to test the AI detection functionality.
        The service should be able to analyze this text and provide insights.
        """
        response = requests.post(
            f"{SERVICE_URL}/api/detect-ai",
            json={"text": test_text},
            timeout=10
        )
        if response.status_code == 200:
            data = response.json()
            print(f"[OK] AI detection passed:")
            print(f"  - AI Probability: {data.get('ai_probability')}%")
            print(f"  - Is AI Generated: {data.get('is_ai_generated')}")
            print(f"  - Confidence: {data.get('confidence')}")
            return True
        else:
            print(f"[FAIL] AI detection failed with status {response.status_code}")
            print(f"  Response: {response.text}")
            return False
    except Exception as e:
        print(f"[FAIL] Error: {str(e)}")
        return False

def test_file_similarity():
    """Test file similarity endpoint"""
    print("\nTesting /api/check-file-similarity endpoint...")
    try:
        test_data = {
            "text": "This is a sample document about machine learning and artificial intelligence.",
            "existing_files": [
                {
                    "id": 1,
                    "text": "This document discusses artificial intelligence and machine learning concepts.",
                    "file_name": "test1.pdf"
                },
                {
                    "id": 2,
                    "text": "Python programming language is widely used in data science.",
                    "file_name": "test2.pdf"
                }
            ]
        }
        response = requests.post(
            f"{SERVICE_URL}/api/check-file-similarity",
            json=test_data,
            timeout=30
        )
        if response.status_code == 200:
            data = response.json()
            print(f"[OK] File similarity check passed:")
            print(f"  - Similarity: {data.get('similarity_percentage')}%")
            print(f"  - Similar Files: {len(data.get('similar_files', []))}")
            print(f"  - AI Probability: {data.get('ai_probability')}%")
            return True
        else:
            print(f"[FAIL] File similarity check failed with status {response.status_code}")
            print(f"  Response: {response.text}")
            return False
    except Exception as e:
        print(f"[FAIL] Error: {str(e)}")
        return False

def test_project_similarity():
    """Test project similarity endpoint"""
    print("\nTesting /api/check-similarity endpoint...")
    try:
        test_data = {
            "project": {
                "title": "Test Project",
                "description": "This is a test project description",
                "objectives": "The objective is to test the similarity service"
            },
            "existing_projects": [
                {
                    "id": 1,
                    "title": "Existing Project",
                    "description": "This is an existing project description",
                    "objectives": "The objective is similar to test project"
                }
            ]
        }
        response = requests.post(
            f"{SERVICE_URL}/api/check-similarity",
            json=test_data,
            timeout=30
        )
        if response.status_code == 200:
            data = response.json()
            print(f"[OK] Project similarity check passed:")
            print(f"  - Similarity: {data.get('similarity_percentage')}%")
            print(f"  - Similar Projects: {len(data.get('similar_projects', []))}")
            return True
        else:
            print(f"[FAIL] Project similarity check failed with status {response.status_code}")
            print(f"  Response: {response.text}")
            return False
    except Exception as e:
        print(f"[FAIL] Error: {str(e)}")
        return False

def main():
    """Run all tests"""
    print("=" * 50)
    print("SmartGrad AI Similarity Service Test")
    print("=" * 50)
    print(f"\nTesting service at: {SERVICE_URL}\n")
    
    results = []
    
    # Run tests
    results.append(("Health Check", test_health()))
    results.append(("AI Detection", test_detect_ai()))
    results.append(("File Similarity", test_file_similarity()))
    results.append(("Project Similarity", test_project_similarity()))
    
    # Print summary
    print("\n" + "=" * 50)
    print("Test Summary")
    print("=" * 50)
    
    passed = sum(1 for _, result in results if result)
    total = len(results)
    
    for test_name, result in results:
        status = "[PASSED]" if result else "[FAILED]"
        print(f"{test_name}: {status}")
    
    print(f"\nTotal: {passed}/{total} tests passed")
    
    if passed == total:
        print("\n[SUCCESS] All tests passed! Service is working correctly.")
        return 0
    else:
        print("\n[ERROR] Some tests failed. Please check the service configuration.")
        return 1

if __name__ == "__main__":
    try:
        exit_code = main()
        sys.exit(exit_code)
    except KeyboardInterrupt:
        print("\n\nTest interrupted by user")
        sys.exit(1)

