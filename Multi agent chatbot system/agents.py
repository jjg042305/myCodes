# from langchain.agents import initialize_agent, AgentType, Tool
# from langchain_community.utilities.wikipedia import WikipediaAPIWrapper
# from langchain_ollama import ChatOllama
# from langchain.vectorstores import FAISS
# from langchain.embeddings import OllamaEmbeddings
# from langchain.docstore.document import Document
# from langchain_core.messages import HumanMessage, AIMessage
# from langchain_core.tools import ToolException
# from pathlib import Path
# import wikipedia
# import json

# class Agents:
#     def __init__(self, llm=None):
#         self.llm = llm or ChatOllama(model='llama3.2', temperature=0.8, num_predict=256)
#         self.embedding_model = OllamaEmbeddings(model="llama3.2")
#         self.faiss_db = self._load_or_create_faiss()
#         self.concordia_page_content = self._load_concordia_page()

#         def _handle_error(error: ToolException) -> str:
#             return f"Error occurred: {error.args[0]}"

#         # Redefining tools using custom logic (RAG-inspired)
#         wikipedia_tool = Tool(
#             name="Wikipedia",
#             func=WikipediaQueryRun(api_wrapper=WikipediaAPIWrapper(top_k_results=1, doc_content_chars_max=100, wiki_client=wikipedia)).run,            description="Use for factual or general knowledge questions."
#                         "Respond using properly formatted JSON.",
#             return_direct=True,
#             handle_tool_error=_handle_error,
#         )

#         concordia_tool = Tool(
#             name="ConcordiaSpecialist",
#             func=lambda query: self.llm.invoke("Answer the question about Concordia Computer Science program using the following information:\n" + self.concordia_page_content+"\n This is the user query: " + query).content.strip(),
#             description="Use for questions about Concordia University's Computer Science admissions.",
#             return_direct=True,
#             handle_tool_error=_handle_error,
#         )

#         ai_tool = Tool(
#             name="AISpecialist",
#             func=lambda query: self.llm.invoke( "On the subject of Artificial Intelligence, answer the user's queries: "+query).content.strip(),
#             description="Use for questions related to AI and machine learning.",
#             return_direct=True,
#             handle_tool_error=_handle_error,
#         )

#         simple_tool = Tool(
#             name="SimpleResponder",
#             func=lambda query: self.llm.invoke(
#         f"You are a helpful assistant. The user has sent the following message:\n\n"
#         f"\"{query}\"\n\n"
#         f"Your task is to reply to this message clearly and concisely. "
#         f"ONLY respond to this message. Do not continue the conversation. "
#         f"Do not suggest further help. Do not ask follow-up questions."
#         f"Do NOT include Observation, Thought, or any follow-up."
#         f"Do NOT expect any other agent or observer to use your output."
#         f"Just give a one-time response to what was asked. Nothing else."
#         f"Do NOT use this tool for admissions-related queries, academic programs, or technical topics. "

#     ).content.strip(),
#     description="Handles greetings, introductions, and simple personal or small talk questions. "
#                 "Use this tool only when the user message is light and doesn't require searching for factual info. "
#                 "Strictly return a one-time direct response to the current message only.",
#             handle_tool_error=_handle_error,
#         )

#         # Final agents with tools
#         self.wikipedia_agent = initialize_agent(
#             [wikipedia_tool, simple_tool], self.llm,
#             agent=AgentType.CHAT_CONVERSATIONAL_REACT_DESCRIPTION,
#             verbose=True, handle_parsing_errors=True
#         )

#         self.concordia_agent = initialize_agent(
#             [concordia_tool, simple_tool], self.llm,
#             agent=AgentType.CHAT_CONVERSATIONAL_REACT_DESCRIPTION,
#             verbose=True, handle_parsing_errors=True
#         )

#         self.ai_agent = initialize_agent(
#             [ai_tool, simple_tool], self.llm,
#             agent=AgentType.CHAT_CONVERSATIONAL_REACT_DESCRIPTION,
#             verbose=True, handle_parsing_errors=True
#         )

#     def _load_or_create_faiss(self):
#         index_path = Path("faiss_index")
#         if index_path.exists() and (index_path / "index.faiss").exists():
#             return FAISS.load_local("faiss_index", self.embedding_model, allow_dangerous_deserialization=True)
#         dummy_doc = Document(page_content="Initialized FAISS.")
#         db = FAISS.from_documents([dummy_doc], self.embedding_model)
#         db.save_local("faiss_index")
#         return db

#     def _load_concordia_page(self):
#         try:
#             from langchain_community.document_loaders import WebBaseLoader
#             loader = WebBaseLoader("https://www.concordia.ca/academics/undergraduate/computer-science.html")
#             docs = loader.load()
#             return "\n".join([doc.page_content for doc in docs])
#         except Exception as e:
#             return f"Error loading Concordia content: {e}"

#     def _convert_to_langchain_messages(self, chat_history):
#         messages = []
#         for user_msg, bot_msg in chat_history:
#             if user_msg:
#                 messages.append(HumanMessage(content=user_msg))
#             if bot_msg:
#                 messages.append(AIMessage(content=bot_msg))
#         return messages

#     def _store_conversation(self, user_input: str, agent_response: str):
#         doc = Document(page_content=f"input: {user_input}\noutput: {agent_response}")
#         self.faiss_db.add_documents([doc])
#         self.faiss_db.save_local("faiss_index")

#     def _rag_wikipedia_tool(self, query: str) -> str:
#         try:
#             context = self._retrieve_context(query)
#             wiki_result = WikipediaAPIWrapper(
#                 top_k_results=1,
#                 doc_content_chars_max=500,
#                 wiki_client=wikipedia
#             ).run(query)
#             combined = f"""You are answering a user's question. Here's the memory and Wikipedia data that might help:

#         Recent memory:
#         {context}

#         Wikipedia summary:
#         {wiki_result}

#         Now answer the user's current question:
#         {query}

#         If it's a follow-up, use context from memory and Wikipedia to give a precise answer to the specific question."""            
#             return self.llm.invoke(combined).content.strip()
#         except Exception as e:
#             return f"Wikipedia tool error: {e}"

#     def _rag_concordia_tool(self, query: str) -> str:
#         try:
#             context = self._retrieve_context(query)
#             prompt = f"""You are answering a user's question about Concordia University's Computer Science program.

#         Recent memory:
#         {context}

#         Concordia program info:
#         {self.concordia_page_content}

#         Now answer the user's current question:
#         {query}

#         If it's a follow-up, use the memory and Concordia info to give a specific, helpful answer."""
#             return self.llm.invoke(prompt).content.strip()
#         except Exception as e:
#             return f"Concordia tool error: {e}"

    

#     # def _rag_ai_tool(self, query: str) -> str:
#     #     try:
#     #         context = self._retrieve_context(query)
#     #         prompt = f"""You are an expert AI tutor.

#     # Recent memory:
#     # {context}

#     # Now answer the user's current question:
#     # {query}

#     # Respond clearly and helpfully. You may use paragraphs or short answers as needed.
#     # If it's a follow-up, do not repeat earlier answers or restate definitions. Instead, respond specifically to the new question using memory and context.

    
#     # Return your full response inside this JSON:
#     # {{ "action": "Final Answer", "action_input": "<your answer here>" }}
#     # You are the final responder. Once you output this, the conversation is over.
#     # Do NOT include Observation, Thought, or any follow-up.
#     # Do NOT expect any other agent or observer to use your output.
#     # Your response should be treated as the final answer. End it there.
#     # Make sure the JSON is strictly valid. Use double quotes around all strings.
#     # Do not include unescaped newlines, tabs, or invalid characters. 
#     # DO NOT include markdown formatting (like asterisks, bold, or headers) in your response.
#     # """

#     #         raw = self.llm.invoke(prompt).content.strip()
#     #         print("\n[DEBUG] Raw LLM output BEFORE cleanup:\n", raw)


#     #         if raw.startswith("```json"):
#     #             raw = raw.replace("```json", "").replace("```", "").strip()
            
#     #         print("\n[DEBUG] Raw LLM output AFTER cleanup:\n", raw)
#     #         try:
#     #             parsed = json.loads(raw)
#     #             print("\n[DEBUG] Parsed JSON:\n", parsed)

#     #             return parsed.get("action_input") or parsed.get("answer_input") or parsed.get("answer") or raw
#     #         except json.JSONDecodeError as e:
#     #             print("[DEBUG] JSONDecodeError:", e)

#     #             try:
#     #                 safe_json = json.loads(f'''
#     #                 {{
#     #                     "action": "Final Answer",
#     #                     "action_input": {json.dumps(raw)}
#     #                 }}
#     #                 ''')
#     #                 return safe_json["action_input"]
#     #             except Exception as fallback_error:
#     #                 print(f"[DEBUG] Fallback also failed: {fallback_error}")
#     #                 return f"AI tool error: {fallback_error}"
                    
#     #     except Exception as e:
#     #         return f"AI tool error: {e}"




#     def _retrieve_context(self, query: str) -> str:
#         docs = self.faiss_db.similarity_search(query, k=3)
#         return "\n".join(doc.page_content for doc in docs) if docs else ""

#     def wikipedia_agentrun(self, query: str, chat_history=None):
#         messages = self._convert_to_langchain_messages(chat_history or [])
        
#         # Inject context for follow-up queries
#         if chat_history:
#             last_user_query = chat_history[-1][0]
#             full_query = f"This is a follow-up to a previous question: '{last_user_query}'. Now answer this: {query}"
#         else:
#             full_query = query

#         # Invoke the Wikipedia agent with context-aware input
#         result = self.wikipedia_agent.invoke({"input": full_query, "chat_history": messages})["output"]
        
#         self._store_conversation(query, result)
#         return result


#     def concordia_agentrun(self, query: str, chat_history=None):
#         messages = self._convert_to_langchain_messages(chat_history or [])
#         result = self.concordia_agent.invoke({"input": query, "chat_history": messages})["output"]
#         self._store_conversation(query, result)
#         return result

#     def ai_specialist_agentrun(self, query: str, chat_history=None):
#         messages = self._convert_to_langchain_messages(chat_history or [])
#         result = self.ai_agent.invoke({"input": query, "chat_history": messages})["output"]
#         self._store_conversation(query, result)
#         return result


import shutil
from langchain.agents import initialize_agent, AgentType, Tool
from langchain_community.document_loaders import WebBaseLoader
from langchain_community.tools import WikipediaQueryRun
from langchain_community.utilities.wikipedia import WikipediaAPIWrapper
from langchain_ollama import ChatOllama
from langchain_core.tools import ToolException
import wikipedia
from langchain.vectorstores import FAISS
from langchain_core.messages import HumanMessage, AIMessage, BaseMessage
from langchain.embeddings import OllamaEmbeddings
from langchain.docstore.document import Document
from pathlib import Path
import wikipedia
import json

class Agents:
    def __init__(self, llm):
        if llm is None:
            self.llm = ChatOllama(model='llama3.2', temperature=0.8, num_predict=256)
        else:
            self.llm = llm

        def _handle_error(error: ToolException) -> str:
            return (
                    "The following errors occurred during tool execution:"
                    + error.args[0]
                    + "Please try another tool."
            )
        
        self.embedding_model = OllamaEmbeddings(model="llama3.2")

        index_path = Path("faiss_index")
        if index_path.exists():
            shutil.rmtree(index_path)
        self.faiss_db = self._load_or_create_faiss()

        # Define Tools
        wikipedia_tool = Tool(
            name="Wikipedia",
            func=WikipediaQueryRun(api_wrapper=WikipediaAPIWrapper(
                top_k_results=1,
                doc_content_chars_max=100,
                wiki_client=wikipedia
            )).run,
            description="Use **only** for general knowledge, public topics, sports, history or any topics that is NOT related to Concordia University or Artificial Intelligence.",
            handle_tool_error=_handle_error
        )


        
        def concordia_func():
            try:
                loader = WebBaseLoader("https://www.concordia.ca/academics/undergraduate/computer-science.html")
                docs = loader.load()
                if not docs:
                    raise ToolException("Could not retrieve Concordia information.")
                page_content = "\n".join([doc.page_content for doc in docs])

                return page_content
            except Exception as e:
                raise ToolException(f"Concordia tool error: {e}")

        self.concordia_page_content = concordia_func()

        # concordia_tool = Tool(
        #     name="ConcordiaSpecialist",
        #     func=lambda query: self.llm.invoke(
        #     f"You are a Concordia University admissions specialist. "
        #         f"Answer the user's question using ONLY the content from the official Concordia Computer Science page. "
        #         f"Do not refer to Wikipedia, outside sources, other agents, or tools. "
        #         f"This is the final response — it will be sent directly to the user. No follow-up questions or suggestions. "
        #         f"Respond clearly and directly.\n\n"
        #         f"Answer **only** using the following official Concordia University Computer Science page content:\n"
        #         f"{self.concordia_page_content}\n\n"
        #         f"Query: {query}").content.strip(),
        #     description="Use **only** for questions strictly related to Concordia University's Computer Science program — admissions, degrees, requirements, deadlines. Do NOT use for any unrelated topics.",
        #     return_direct = True,
        #      handle_tool_error=_handle_error
        # )

        concordia_tool = Tool(
                name="ConcordiaSpecialist",
                func=lambda query: self._quote_wrap(self.llm.invoke("Answer the question about Concordia Computer Science program using the following information:\n" + self.concordia_page_content+"\n This is the user query: " + query).content.strip()),
                description="Information about Concordia Computer Science program admissions. Do NOT use this for anything unrelated to Concordia Computer Science program admissions.",
                return_direct= True,
                handle_tool_error= _handle_error, )

        ai_tool = Tool(
                name="AISpecialist",
                func=lambda query: self._quote_wrap(self.llm.invoke( "On the subject of Artificial Intelligence, answer the user's queries: "+query).content.strip()),
                description="AI-specific information only (artificial intelligence, machine learning, neural networks, deep learning, etc ). Do NOT use this for anything unrelated to AI",
                handle_tool_error=_handle_error, )
            
        fallback_tool = Tool(
            name="FallbackResponder",
            func=lambda query: self.llm.invoke(
                f"""You are a helpful assistant. The user asked:\n"{query}"\n\n
                If it's a general or factual question, answer it clearly and directly.
                If it's a greeting, respond kindly.
                Keep the answer short, natural, and avoid asking follow-up questions.
                Do NOT say you're unsure or suggest using a different tool. Just give your best possible answer."""
            ).content.strip(),
            description="Fallback responder for general knowledge or simple questions not answered by Wikipedia.",
            return_direct=True,
            handle_tool_error=_handle_error,)

        # Initialize Individual Agents using create_react_agent
        self.wikipedia_agent = initialize_agent( [wikipedia_tool, fallback_tool],self.llm, agent=AgentType.CHAT_CONVERSATIONAL_REACT_DESCRIPTION, verbose=True,  handle_parsing_errors=True)
        self.concordia_agent = initialize_agent( [concordia_tool], self.llm, agent=AgentType.CHAT_CONVERSATIONAL_REACT_DESCRIPTION, verbose=True,  handle_parsing_errors=True)
        self.ai_agent = initialize_agent( [ai_tool], self.llm, agent=AgentType.CHAT_CONVERSATIONAL_REACT_DESCRIPTION, verbose=True,  handle_parsing_errors=True)

    def _load_or_create_faiss(self):
        index_path = Path("faiss_index")
        if index_path.exists() and (index_path / "index.faiss").exists():
            return FAISS.load_local("faiss_index", self.embedding_model, allow_dangerous_deserialization=True)
        dummy_doc = Document(page_content="Initialized FAISS.")
        db = FAISS.from_documents([dummy_doc], self.embedding_model)
        db.save_local("faiss_index")
        return db
            
    # def _retrieve_context(self, query: str) -> str:
    #         docs = self.faiss_db.similarity_search(query, k=3)
    #         return "\n".join(doc.page_content for doc in docs) if docs else ""

    def _retrieve_context(self, query: str) -> list[BaseMessage]:
        print(f'We are inside retrieve_context. query : {query}')
        docs = self.faiss_db.similarity_search(query, k=3)
        print(f'docs is {docs}')
        context = []
        for doc in docs:
            if "human:" in doc.page_content and "ai:" in doc.page_content:
                try:
                    human, ai = doc.page_content.split("ai:")
                    user_text = human.replace("human:", "").strip()
                    ai_text = ai.strip()
                    context.extend([HumanMessage(content=user_text), AIMessage(content=ai_text)])
                except ValueError:
                    continue
        return context

    def _store_conversation(self, user_input: str, agent_response: str): # agent response : message, user input : query
            print(f'Insde store conversation now. user_input is {user_input} agent_response is {agent_response}')
            text = f'human: {user_input}\nai: {agent_response}'
            doc = Document(page_content=text)
            print(f'text is {text} and doc is {doc}')
            self.faiss_db.add_documents([doc])
            self.faiss_db.save_local("faiss_index")
            # each document should be formatted as a dict and output a list of dict   
            # format necessary for chat history     
            
    def wikipedia_agentrun(self, query: str):
        print(f'We are inside wikipedia_agentrun. query is {query}')
        context = self._retrieve_context(query)
        print(f'Back inside wikipedia_agentrun. context is {context}')
        response = self.wikipedia_agent.invoke({"input":query, "chat_history": context})["output"]
        print(f'response is {response}')
        self._store_conversation(query, response)
        return response


    def concordia_agentrun(self, query: str):
        print(f'We are inside concordia_agentrun. query is {query}')
        context = self._retrieve_context(query)
        print(f'Back inside concordia_agentrun. context is {context}')
        response = self.concordia_agent.invoke({"input":query, "chat_history": context})["output"]
        print(f'response is {response}')
        self._store_conversation(query, response)
        return response


    def ai_specialist_agentrun(self, query: str):
        print(f'We are inside ai_specialist_agentrun. query is {query}')
        context = self._retrieve_context(query)
        print(f'Back inside ai_specialist_agentrun. context is {context}')
        response = self.ai_agent.invoke({"input":query, "chat_history": context})["output"]
        print(f'response is {response}')
        self._store_conversation(query, response)
        return response
    

    def _quote_wrap(self, response:str) -> str:
        if not (response.startswith('"') and response.endswit('"')):
            return f'"{response}"'
    
    # def _convert_to_langchain_messages(self, chat_history_raw):
    #     messages = []
    #     for user_msg, bot_msg in chat_history_raw:
    #         if user_msg:
    #             messages.append(HumanMessage(content=user_msg))
    #         if bot_msg:
    #             messages.append(AIMessage(content=bot_msg))
    #     return messages
        