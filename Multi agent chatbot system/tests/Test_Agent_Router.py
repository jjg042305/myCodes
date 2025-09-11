import pytest
from unittest.mock import MagicMock
from agent_router import AgentRouter

def test_classifier_wikipedia():
    router = AgentRouter()
    assert router.classifier("What is the capital of France?") == "wikipedia"

def test_classifier_concordia():
    router = AgentRouter()
    assert router.classifier("How can I apply to Concordia's Computer Science program?") == "concordiaspecialist"

def test_classifier_ai():
    router = AgentRouter()
    assert router.classifier("Explain neural networks.") == "aispecialist"

def test_classifier_invalid():
    router = AgentRouter()
    assert router.classifier("What is the weather today?") == "wikipedia"

@pytest.fixture
def agent_router():
    router = AgentRouter()
    router.agent_methods = {
        "wikipedia": MagicMock(return_value="Wikipedia Response"),
        "concordiaspecialist": MagicMock(return_value="Concordia Response"),
        "aispecialist": MagicMock(return_value="AI Response"),
    }
    return router

def test_route_query_wikipedia(agent_router):
    agent_router.classifier = MagicMock(return_value="wikipedia")
    response = agent_router.route_query("General knowledge question")
    assert response == {"response": "Wikipedia Response"}
    agent_router.agent_methods["wikipedia"].assert_called_once_with("General knowledge question")

def test_route_query_concordiaspecialist(agent_router):
    agent_router.classifier = MagicMock(return_value="concordiaspecialist")
    response = agent_router.route_query("Tell me about Concordia admissions")
    assert response == {"response": "Concordia Response"}
    agent_router.agent_methods["concordiaspecialist"].assert_called_once_with("Tell me about Concordia admissions")

def test_route_query_aispecialist(agent_router):
    agent_router.classifier = MagicMock(return_value="aispecialist")
    response = agent_router.route_query("Explain neural networks")
    assert response == {"response": "AI Response"}
    agent_router.agent_methods["aispecialist"].assert_called_once_with("Explain neural networks")

def test_route_query_invalid_classification(agent_router):
    agent_router.classifier = MagicMock(return_value="unknown")
    with pytest.raises(Exception) as exc_info:
        agent_router.route_query("Unknown query")
    assert exc_info.value.status_code == 400
    assert "Unable to classify query." in str(exc_info.value)
