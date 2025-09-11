# COMP474Project2: Multi-Agent Chatbot System

This project implements a multi-agent chatbot system that leverages Ollama for intelligent conversations across multiple domains. The chatbot adapts dynamically based on context, past interactions, and external knowledge sources.

One agent uses Wikipedia for general questions, another gathers information from the Concordia University website to answer inquiries specifically for admission to Concordia in the Computer Science program, and a third handles AI-related questions.

## Execution Guide

Follow these steps to set up and run the chatbot system:

**1. Prerequisites:**

* **Python 3.12:** Ensure you have Python 3.12 installed on your system. You can download it from the official Python website: [python.org](https://www.python.org/downloads/). If you are using windows, you will find it easier to download it from the Windows Store.
* **Ollama:** Make sure you have Ollama installed and running. This project utilizes Ollama for language model interactions. Follow the Ollama installation instructions for your operating system: [ollama.ai](https://ollama.ai/)
* **Models:** Pull the model that is used on this code using the following command in your terminal: `ollama pull llama3.2`.

**2. Set Up the Virtual Environment (Recommended):**

* Open your terminal (macOS/Linux) or PowerShell/Command Prompt (Windows) and navigate to the project directory.

* Activate the virtual environment:

    * **macOS/Linux (Bash):**

        ```bash
        source venv/bin/activate
        ```

    * **Windows (PowerShell / Command Prompt):**

        ```powershell
        venv\Scripts\activate
        ```

**3. Install Dependencies:**

* Upgrade pip (Python package installer):

    ```bash
    python.exe -m pip install --upgrade pip
    ```

* Install the required packages from the `requirements.txt` file:

    ```bash
    pip install -r ./requirements.txt
    ```

    **Packages included in `requirements.txt`:**

    * `python-dotenv`
    * `pytest~=8.3.5`
    * `fastapi~=0.115.12`
    * `uvicorn`
    * `pydantic~=2.10.6`
    * `wikipedia~=1.4.0`
    * `langchain~=0.3.21`
    * `langchain-community~=0.3.20`
    * `langchain-core~=0.3.48`
    * `langchain_ollama`
    * `langgraph~=0.3.20`
    * `chromadb~=0.6.3`

**4. Run the Application:**

* Start the  application (FastAPI) using Uvicorn:

    ```bash
    uvicorn main:app --reload
    ```

    * `main:app` refers to the `app` object in your `main.py` file.
    * `--reload` enables automatic reloading of the server when you make changes to your code.

**5. Access the Chatbot UI:**

* Once the Uvicorn server is running, open your web browser and navigate to `http://127.0.0.1:8000`.
* You can then interact with the chatbot through the user interface.

**7. Testing (Optional):**

* To run the included test using pytest you can use the run button or:

    ```bash
    pytest
    ```

**Important Notes:**

* Ensure that Ollama is running in the background before starting the application.
* The chatbot's performance depends on the Ollama model used and the quality of the data sources (Wikipedia, Concordia University website).
* If you encounter any issues, ensure that all dependencies are installed correctly and that your environment is properly configured.