from flask import Flask, request, jsonify
from sentence_transformers import SentenceTransformer, util
import torch

app = Flask(__name__)
model = SentenceTransformer('all-MiniLM-L6-v2')

@app.route('/rag-search', methods=['POST'])
def rag_search():
    data = request.json
    query = data['query']
    items = data['items']

    # Encode query & item descriptions
    query_emb = model.encode(query, convert_to_tensor=True)
    item_texts = [item['Description'] for item in items.values()]
    item_embs = model.encode(item_texts, convert_to_tensor=True)

    # Compute cosine similarity
    cos_scores = util.cos_sim(query_emb, item_embs)[0]
    k = min(5, len(item_embs))  # ensure k is never larger than dataset
    top_results = torch.topk(cos_scores, k=k)

    filtered = {}
    for idx in top_results.indices.tolist():
        filtered[str(idx+1)] = list(items.values())[idx]

    return jsonify(filtered)

if __name__ == '__main__':
    app.run(port=5000, debug=True)
