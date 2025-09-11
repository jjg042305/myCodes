import unittest
from unittest.mock import patch, MagicMock
from langchain_core.tools import ToolException

# Assuming your Agents class is in a file named 'your_module.py'
from agents import Agents

class TestTools(unittest.TestCase):

    def setUp(self):
        self.agents = Agents()

    @patch('langchain_community.utilities.wikipedia.WikipediaAPIWrapper.run')
    def test_wikipedia_tool_success(self, mock_wikipedia_run):
        """Test the Wikipedia tool directly with a successful query."""
        mock_wikipedia_run.return_value = "Wikipedia summary about Canada"
        query = "What is Canada?"
        result = self.agents.wikipedia_agent.tools[0].func(query)
        self.assertIn("Canada", result)
        mock_wikipedia_run.assert_called_once_with(query)

    @patch('langchain_community.utilities.wikipedia.WikipediaAPIWrapper.run')
    def test_wikipedia_tool_error(self, mock_wikipedia_run):
        """Test the Wikipedia tool directly with a query that results in an error."""
        mock_wikipedia_run.side_effect = Exception("Wikipedia error")
        query = "Something that doesn't exist"
        try:
            self.agents.wikipedia_agent.tools[0].func(query)
            self.fail("Expected ToolException to be raised")
        except ToolException as e:
            self.assertIn("The following errors occurred during tool execution:", str(e))
            self.assertIn("Please try another tool.", str(e))
        mock_wikipedia_run.assert_called_once_with(query)

    @patch('langchain_community.document_loaders.WebBaseLoader.load')
    @patch('langchain_ollama.ChatOllama.invoke')
    def test_concordia_tool_success(self, mock_llm_invoke, mock_loader_load):
        """Test the Concordia tool directly with a successful query."""
        mock_loader_load.return_value = [MagicMock(page_content="Concordia CS program details.")]
        mock_llm_invoke.return_value.content = "Concordia CS program offers A, B, C."
        query = "Tell me about Concordia Computer Science program."
        result = self.agents.concordia_agent.tools[0].func(query)
        self.assertIn("Concordia CS program offers", result)
        mock_llm_invoke.assert_called_once()
        mock_loader_load.assert_called_once()

    @patch('langchain_community.document_loaders.WebBaseLoader.load')
    def test_concordia_tool_loader_error(self, mock_loader_load):
        """Test the Concordia tool directly with an error during web page loading."""
        mock_loader_load.return_value = []
        query = "Concordia admissions for CS."
        with self.assertRaisesRegex(ToolException, "Could not retrieve Concordia information."):
            self.agents.concordia_agent.tools[0].func(query)
        mock_loader_load.assert_called_once()

    @patch('langchain_community.document_loaders.WebBaseLoader.load')
    @patch('langchain_ollama.ChatOllama.invoke')
    def test_concordia_tool_llm_error(self, mock_llm_invoke, mock_loader_load):
        """Test the Concordia tool directly with an error during LLM invocation."""
        mock_loader_load.return_value = [MagicMock(page_content="Concordia CS program details.")]
        mock_llm_invoke.side_effect = Exception("LLM processing error")
        query = "What are the requirements for Concordia CS?"
        with self.assertRaisesRegex(ToolException, "Concordia tool error: LLM processing error"):
            self.agents.concordia_agent.tools[0].func(query)
        mock_llm_invoke.assert_called_once()
        mock_loader_load.assert_called_once()

    @patch('langchain_ollama.ChatOllama.invoke')
    def test_ai_tool_success(self, mock_llm_invoke):
        """Test the AI tool directly with a successful query."""
        mock_llm_invoke.return_value.content = "AI is a field of study."
        query = "What is AI?"
        result = self.agents.ai_agent.tools[0].func(query)
        self.assertIn("AI is a field of study.", result)
        mock_llm_invoke.assert_called_once_with("On the subject of Artificial Intelligence, answer the user's queries: What is AI?")

    @patch('langchain_ollama.ChatOllama.invoke')
    def test_ai_tool_error(self, mock_llm_invoke):
        """Test the AI tool directly with an error during LLM invocation."""
        mock_llm_invoke.side_effect = Exception("Ollama error")
        query = "Explain deep learning."
        with self.assertRaisesRegex(ToolException, "Ollama error"):
            self.agents.ai_agent.tools[0].func(query)
        mock_llm_invoke.assert_called_once_with("On the subject of Artificial Intelligence, answer the user's queries: Explain deep learning.")

class TestAgentsWithTools(unittest.TestCase):

    def setUp(self):
        self.agents = Agents()


    def test_concordia_agent(self):
        response= self.agents.concordia_agentrun("What are the admission requirements for Computer Science")
        self.assertEqual(response, "None")

    def test_ai_agent(self):
        response= self.agents.ai_specialist_agentrun("What is machine learning in one sentence")
        print(response)
        self.assertEqual("None", response)

    def test_wikipedia_agent(self):
        response= self.agents.wikipedia_agentrun("Where is Canada?")
        print(response)
        self.assertEqual("None", response)

if __name__ == '__main__':
    unittest.main()