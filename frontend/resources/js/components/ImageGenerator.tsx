import React, { useState } from 'react';
import { Button } from "./ui/button";
import { Card } from "./ui/card";
import { Camera, X, Sparkles, Palette, Monitor, Image as ImageIcon, Wand2, ChevronDown } from "lucide-react";
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from "./ui/select";
import { Label } from "./ui/label";
import { toast } from "react-hot-toast";
import { useCredits } from "../lib/CreditsContext";

const ImageGenerator = ({
  editorContent,
  customTopic,
  mediaData,
  mediaType,
  setMediaData,
  setMediaType,
  setMediaFile,
  generatedImagePrompt,
  setGeneratedImagePrompt,
  isImgGenerating,
  setIsImgGenerating,
  disabled,
}) => {
  const [aspectRatio, setAspectRatio] = useState("1:1");
  const [resolution, setResolution] = useState("512x512");
  const [style, setStyle] = useState("realistic");
  const { refreshCredits } = useCredits();

  const aspectRatioOptions = [
    { value: "1:1", label: "Square", icon: "□", desc: "Perfect for social media" },
    { value: "4:3", label: "Standard", icon: "▭", desc: "Classic photo format" },
    { value: "16:9", label: "Widescreen", icon: "▬", desc: "Cinematic landscape" },
    { value: "3:2", label: "Photo", icon: "▯", desc: "Traditional photography" },
    { value: "9:16", label: "Portrait", icon: "▮", desc: "Mobile-first vertical" }
  ];

  const resolutionOptions = [
    { value: "512x512", label: "Standard", desc: "Fast generation", quality: "Basic" },
    { value: "1024x1024", label: "High Quality", desc: "Balanced quality", quality: "Premium" },
    { value: "2048x2048", label: "Ultra HD", desc: "Maximum detail", quality: "Professional" }
  ];

  const styleOptions = [
    { value: "realistic", label: "Realistic", icon: "📷", desc: "Photographic quality" },
    { value: "cartoonify", label: "Cartoon", icon: "🎨", desc: "Playful and fun" },
    { value: "cardboard", label: "Cardboard", icon: "📦", desc: "Paper craft style" },
    { value: "film-noir", label: "Film Noir", icon: "🎬", desc: "Classic black & white" },
    { value: "watercolor", label: "Watercolor", icon: "🖌️", desc: "Artistic paint effect" },
    { value: "cyberpunk", label: "Cyberpunk", icon: "🌆", desc: "Futuristic neon" },
    { value: "oil-painting", label: "Oil Painting", icon: "🖼️", desc: "Classical art style" }
  ];

  const handleGenerateImage = async () => {

    if (!editorContent && !customTopic) {
      toast.error("Please write or generate post content first.");
      return;
    }
    setIsImgGenerating(true);
    setGeneratedImagePrompt("");
    try {
      const res = await fetch("/api/generate-image-idea", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        credentials: "include",
        body: JSON.stringify({
          post_content: editorContent?.replace(/<[^>]+>/g, "") || customTopic,
          aspect_ratio: aspectRatio,
          resolution: resolution,
          style: style
        }),
      });
      const data = await res.json();
      if (res.ok && data.image_url) {
        setMediaData(data.image_url);
        setMediaType("image");
        setMediaFile(null);
        setGeneratedImagePrompt(data.image_prompt || "");
        toast.success("AI image generated!");
        if (typeof refreshCredits === "function") refreshCredits();
      } else if (data.error && data.error.toLowerCase().includes("credit")) {
        toast.error(
          data.error ||
          "You have used all your credits. Please purchase or renew your plan to continue generating posts.",
          { duration: 7000 }
        );
      } else {
        toast.error(data.error || "Failed to generate image.");
      }
    } catch (err) {
      toast.error("Error generating image.");
    } finally {
      setIsImgGenerating(false);
    }
  };

  return (
    <Card className="relative overflow-hidden border-0 shadow-lg bg-gradient-to-br from-card via-card/95 to-muted/30 mt-5 ">
      {/* Animated background elements */}
      <div className="absolute inset-0 bg-gradient-to-br from-primary/5 via-transparent to-accent/5"></div>
      <div className="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-primary/10 to-transparent rounded-full blur-3xl"></div>
      <div className="absolute bottom-0 left-0 w-24 h-24 bg-gradient-to-tr from-accent/10 to-transparent rounded-full blur-2xl"></div>

      {/* Header */}
      <div className="relative p-6 bg-gradient-to-r from-blue-50 to-indigo-50 border-b">
        <div className="flex items-center gap-3">
          <div className="relative">
            <Sparkles className="text-[#f2cf19]" size={20} />
          </div>
          <div>
            <h3 className="text-xl font-semibold flex items-center gap-2 text-gray-800">
              AI Image Generator
            </h3>
            <p className="text-sm text-muted-foreground">
              AI will analyze your content to create the perfect visual
            </p>
          </div>
        </div>
      </div>

      {/* Main Content */}
      <div className="relative p-6 space-y-6">
        {/* Creative Options Grid */}
        <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
          {/* Aspect Ratio */}
          <div className="space-y-3">
            <Label className="text-sm font-semibold flex items-center gap-2 text-foreground">
              <Monitor className="w-4 h-4 text-[#0000ff]" />
              Aspect Ratio
            </Label>
            <Select value={aspectRatio} onValueChange={setAspectRatio}>
              <SelectTrigger className="w-full h-12 bg-background/60 backdrop-blur border-border/60 hover:border-primary/50 transition-all duration-200 shadow-sm hover:shadow-md">
                <SelectValue />
              </SelectTrigger>
              <SelectContent className="bg-background/95 backdrop-blur-xl border-border/50 shadow-2xl">
                {aspectRatioOptions.map((option) => (
                  <SelectItem
                    key={option.value}
                    value={option.value}
                    className="hover:bg-muted/80 focus:bg-muted/80 transition-colors"
                  >
                    <div className="flex items-center gap-3 py-1">
                      <span className="text-lg">{option.icon}</span>
                      <div>
                        <div className="font-medium text-left">{option.label}</div>
                        <div className="text-xs text-muted-foreground">{option.desc}</div>
                      </div>
                    </div>
                  </SelectItem>
                ))}
              </SelectContent>
            </Select>
          </div>

          {/* Resolution */}
          <div className="space-y-3">
            <Label className="text-sm font-semibold flex items-center gap-2 text-foreground">
              <ImageIcon className="w-4 h-4 text-[#0000ff]" />
              Quality
            </Label>
            <Select value={resolution} onValueChange={setResolution}>
              <SelectTrigger className="w-full h-12 bg-background/60 backdrop-blur border-border/60 hover:border-primary/50 transition-all duration-200 shadow-sm hover:shadow-md">
                <SelectValue />
              </SelectTrigger>
              <SelectContent className="bg-background/95 backdrop-blur-xl border-border/50 shadow-2xl">
                {resolutionOptions.map((option) => (
                  <SelectItem
                    key={option.value}
                    value={option.value}
                    className="hover:bg-muted/80 focus:bg-muted/80 transition-colors"
                  >
                    <div className="flex items-center justify-between w-full py-1">
                      <div>
                        <div className="font-medium text-left">{option.label}</div>
                        <div className="text-xs text-muted-foreground">{option.desc}</div>
                      </div>
                      <span className="text-xs px-2 py-1 rounded-full bg-primary/10 text-[#0000ff] font-medium">
                        {option.quality}
                      </span>
                    </div>
                  </SelectItem>
                ))}
              </SelectContent>
            </Select>
          </div>

          {/* Style */}
          <div className="space-y-3">
            <Label className="text-sm font-semibold flex items-center gap-2 text-foreground">
              <Palette className="w-4 h-4 text-[#0000ff]" />
              Artistic Style
            </Label>
            <Select value={style} onValueChange={setStyle}>
              <SelectTrigger className="w-full h-12 bg-background/60 backdrop-blur border-border/60 hover:border-primary/50 transition-all duration-200 shadow-sm hover:shadow-md">
                <SelectValue />
              </SelectTrigger>
              <SelectContent className="bg-background/95 backdrop-blur-xl border-border/50 shadow-2xl max-h-80">
                {styleOptions.map((option) => (
                  <SelectItem
                    key={option.value}
                    value={option.value}
                    className="hover:bg-muted/80 focus:bg-muted/80 transition-colors"
                  >
                    <div className="flex items-center gap-3 py-1">
                      <span className="text-lg">{option.icon}</span>
                      <div>
                        <div className="font-medium text-left">{option.label}</div>
                        <div className="text-xs text-muted-foreground">{option.desc}</div>
                      </div>
                    </div>
                  </SelectItem>
                ))}
              </SelectContent>
            </Select>
          </div>
        </div>

        {/* Action Area */}
        <div className="flex items-center gap-4 ">
          <Button
            onClick={handleGenerateImage}
            disabled={disabled || isImgGenerating}
            className="bg-[#0000ff] hover:bg-[#0000ffc4] font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-[1.02] disabled:hover:scale-100 disabled:opacity-50 border-0 w-auto ml-auto"
          >
            {isImgGenerating ? (
              <div className="flex items-center gap-3">
                <div className="w-5 h-5 border-2 border-primary-foreground/30 border-t-primary-foreground rounded-full animate-spin"></div>
                <span>Creating Magic...</span>
              </div>
            ) : (
              <div className="flex items-center gap-3">
                <Wand2 className="w-5 h-5" />
                <span>{generatedImagePrompt ? "Regenerate Image" : "Generate AI Image"}</span>
                <Sparkles className="w-4 h-4 animate-pulse" />
              </div>
            )}
          </Button>
        </div>
      </div>
    </Card>
  );
};

export default ImageGenerator;