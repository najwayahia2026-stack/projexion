#!/bin/bash

echo "========================================"
echo "SmartGrad AI Similarity Service"
echo "========================================"
echo ""

cd "$(dirname "$0")"

echo "Checking Python installation..."
python3 --version || {
    echo "ERROR: Python 3 is not installed or not in PATH"
    echo "Please install Python 3.8 or higher"
    exit 1
}

echo ""
echo "Installing/updating dependencies..."
pip3 install -r requirements.txt || {
    echo "ERROR: Failed to install dependencies"
    exit 1
}

echo ""
echo "Starting AI Service on http://localhost:8001"
echo "Press Ctrl+C to stop the service"
echo ""

python3 main.py

