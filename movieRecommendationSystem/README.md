<!DOCTYPE html>
<html>
<head>
    <title>Movie Recommendation System Using AI Techniques</title>
</head>
<body>
    <h1><b>Movie Recommendation System Using AI Techniques</b></h1>
    <h2><b>Overview</b></h2>
    <p>
        This project implements a <b>movie recommendation system</b> by leveraging <b>AI and machine learning techniques</b>. The system uses:
    </p>
    <ul>
        <li><b>Content-based filtering</b>: Generates a similarity map of movies based on tags and genres.</li>
        <li><b>Collaborative filtering</b>: Groups movies into clusters using user rating patterns.</li>
    </ul>
    <p>
        The project showcases the application of AI techniques like <b>cosine similarity</b>, <b>clustering (KMeans)</b>, and <b>feature engineering</b> to build meaningful relationships between movies.
    </p>
    <h2><b>Project Structure</b></h2>
    <h3><b>Content-Based Filtering</b></h3>
    <h4><b>Tags and Genres Extraction</b></h4>
    <ul>
        <li>Movies are characterized by their genres, split into individual binary features.</li>
        <li>Tags are processed to assign weighted relevance scores based on user interactions.</li>
    </ul>
    <h4><b>Feature Engineering</b></h4>
    <ul>
        <li>Genres are converted into binary features.</li>
        <li>Tags are weighted by relevance and scaled to create a comprehensive feature matrix for movies.</li>
    </ul>
    <h4><b>Cosine Similarity</b></h4>
    <ul>
        <li>A pairwise similarity matrix is computed to identify similar movies based on their feature vectors.</li>
    </ul>
    <h4><b>Similarity Mapping</b></h4>
    <ul>
        <li>For each movie, a list of movies with a similarity score above a threshold is generated.</li>
    </ul>
    <h3><b>Collaborative Filtering</b></h3>
    <h4><b>User Rating Clustering</b></h4>
    <ul>
        <li>A <b>user-item sparse matrix</b> is created from the ratings dataset.</li>
        <li>Movies are clustered using the <b>KMeans algorithm</b> based on user rating patterns.</li>
    </ul>
    <h4><b>Cluster Mapping</b></h4>
    <ul>
        <li>Each movie is assigned to a cluster, and clusters are mapped to the movies they contain.</li>
    </ul>
    <h2><b>Key Features</b></h2>
    <h4><b>Feature Engineering</b></h4>
    <ul>
        <li>Binary encoding for genres.</li>
        <li>Weighted scaling of tags based on relevance.</li>
    </ul>
    <h4><b>Cosine Similarity</b></h4>
    <ul>
        <li>Captures the similarity between movies based on their genres and tags.</li>
    </ul>
    <h4><b>Clustering with KMeans</b></h4>
    <ul>
        <li>Groups movies into 10 clusters using user ratings to capture collaborative filtering insights.</li>
    </ul>
    <h4><b>Combined Recommendations</b></h4>
    <ul>
        <li>Provides multiple ways to recommend movies:</li>
        <ul>
            <li>Movies similar in content (tags/genres).</li>
            <li>Movies from the same cluster (user behavior).</li>
        </ul>
    </ul>
    <h2><b>AI and Machine Learning Techniques</b></h2>
    <ul>
        <li><b>Content-Based Filtering:</b>
            <ul>
                <li>Used cosine similarity to generate recommendations based on movie metadata.</li>
                <li>Enhanced feature vectors with tag relevance scores.</li>
            </ul>
        </li>
        <li><b>Collaborative Filtering:</b>
            <ul>
                <li>Applied KMeans clustering on normalized user-item matrices to uncover rating-based patterns.</li>
            </ul>
        </li>
        <li><b>Preprocessing and Normalization:</b>
            <ul>
                <li>StandardScaler for normalizing input matrices.</li>
                <li>MinMaxScaler to scale tag relevance scores.</li>
            </ul>
        </li>
    </ul>
    <h2><b>How to Run</b></h2>
    <ol>
        <li><b>Install the necessary libraries:</b>
            <pre>
pip install pandas numpy scikit-learn seaborn matplotlib
            </pre>
        </li>
        <li><b>Load the datasets:</b>
            <ul>
                <li>Ensure the required CSV files (<i>movie.csv</i>, <i>tag.csv</i>, <i>rating.csv</i>, <i>link.csv</i>, <i>genome_scores.csv</i>, <i>genome_tags.csv</i>) are in the working directory.</li>
            </ul>
        </li>
        <li><b>Run the code in the provided order, ensuring that all dependencies are met.</b></li>
    </ol>
    <h2><b>Output</b></h2>
    <ul>
        <li><b>similar_movies_df:</b>
            <ul>
                <li>A DataFrame mapping each movie to a list of similar movies based on content-based filtering.</li>
            </ul>
        </li>
        <li><b>df_clustered_movies:</b>
            <ul>
                <li>A DataFrame mapping each cluster to a list of movies grouped by user rating patterns.</li>
            </ul>
        </li>
    </ul>
    <h3><b>Note:</b></h3>
    <p>
        The datasets used in this project were downloaded from Kaggle's <b>MovieLens 20M Dataset</b>. They are too large to be included directly in this repository. Please download them using the links below:

- [MovieLens 20M Dataset](https://www.kaggle.com/datasets/grouplens/movielens-20m-dataset)

After downloading, place the files in the root directory of the project. I thank Kaggle and GroupLens for providing this valuable data resource.
    </p>
</body>
</html>

