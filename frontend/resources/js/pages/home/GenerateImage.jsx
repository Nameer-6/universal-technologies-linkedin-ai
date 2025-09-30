import React, { useState } from "react";

function GenerateImage() {
  const [prompt, setPrompt] = useState("");
  const [imageUrl, setImageUrl] = useState("");
  const [loading, setLoading] = useState(false);

  const generateImage = async (e) => {
    e.preventDefault();
    setLoading(true);
    setImageUrl("");
    try {
      const res = await fetch("/api/generate-image", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ prompt }),
      });
      const data = await res.json();
      const url = data.data?.[0]?.url;
      setImageUrl(url);
    } catch {
      alert("Failed to generate image");
    }
    setLoading(false);
  };

  return (
    <div style={{ maxWidth: 500, margin: "40px auto", textAlign: "center" }}>
      <h2>OpenAI Image Generator</h2>
      <form onSubmit={generateImage}>
        <input
          value={prompt}
          onChange={(e) => setPrompt(e.target.value)}
          placeholder="Enter prompt"
          style={{ width: "70%" }}
          required
        />
        <button type="submit" disabled={loading}>
          {loading ? "Generating..." : "Generate"}
        </button>
      </form>
      {imageUrl && (
        <div>
          <img src={imageUrl} alt="Generated" style={{ width: "100%", marginTop: 20 }} />
        </div>
      )}
    </div>
  );
}

export default GenerateImage;