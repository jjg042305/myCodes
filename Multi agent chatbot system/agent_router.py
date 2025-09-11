from fastapi import HTTPException
from langchain_ollama import ChatOllama
from agents import Agents

class AgentRouter:
    def __init__(self):
        self.llm = ChatOllama(model='llama3.2', temperature=0.8, num_predict=256,  streaming=False)
        self.agent = Agents(self.llm)
        self.agent_methods = {
            "wikipedia": self.agent.wikipedia_agentrun,
            "concordiaspecialist": self.agent.concordia_agentrun,
            "aispecialist": self.agent.ai_specialist_agentrun,
        }

    def classifier(self, query: str) -> str:
        """prompt = (
            "Classify the following query into one of three categories: "
            "concordiaspecialist' for information about admissions to Concordia University's Computer Science program,including admissions, degrees, requirements, deadlines, etc. "
            "'aispecialist' for information related to Artificial Intelligence, "
            "'wikipedia' for any other subjects. "

            f"Query: {query}\n"
            "Respond with only the category name."
        )"""
        prompt = (
            "You are a classifier. Classify the following user query into one of three categories:\n\n"
            "1. 'concordiaspecialist' — for questions about admissions to Concordia University's Computer Science program, such as application requirements, deadlines, degree options, or university policies.\n"
            "2. 'aispecialist' — for technical questions related to artificial intelligence, including topics like machine learning, deep learning, neural networks, AI models, algorithms, or applications of AI.\n"
            "3. 'wikipedia' — for general knowledge, facts, public topics (e.g., history, geography, math, famous people) that are not specifically about Concordia or AI.\n\n"
            
            "Examples:\n"
            "Query: What is machine learning?\n"
            "Answer: aispecialist\n\n"
            "Query: What is deep learning?\n"
            "Answer: aispecialist\n\n"
            "Query: What is the capital of France?\n"
            "Answer: wikipedia\n\n"
            "Query: Who invented the telephone?\n"
            "Answer: wikipedia\n\n"
            "Query: How do I apply to Concordia's CS program?\n"
            "Answer: concordiaspecialist\n\n"
            "Query: What GPA do I need for Concordia Computer Science?\n"
            "Answer: concordiaspecialist\n\n"
            
            f"Now classify this query:\n"
            f"Query: {query}\n"
            "Respond with only the category name."
            )

        agent_assigned = self.llm.invoke(prompt).content.strip().lower()
        valid_categories = ["wikipedia", "concordiaspecialist", "aispecialist"]

        return agent_assigned if agent_assigned in valid_categories else "wikipedia"

    def route_query(self, query: str):
        agent_type = self.classifier(query)
        try:
            return  self.agent_methods[agent_type](query)
        except KeyError:
            raise HTTPException(status_code=400, detail="Unable to classify query.")
