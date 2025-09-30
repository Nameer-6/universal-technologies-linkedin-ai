import React from "react";
import { Trash2, Pencil, Save } from "lucide-react";

const SlideCard = (props) => {
  const {
    slide,
    onDelete,
    showFooter = false,
    footerText = "",
    isEditing = false,
    onEdit,
    onSave,
    onTitleChange,
    onContentChange
  } = props;

  return (
    <div className="border border-gray-200 rounded-lg m-2 p-5 bg-white min-h-[200px] flex flex-col relative text-center">
      <div className="absolute top-2 right-2 flex gap-2">
        {onDelete && (
          <button
            className="p-1 rounded-full hover:bg-gray-100"
            onClick={onDelete}
            title="Delete Slide"
          >
            <Trash2 className="h-4 w-4 text-red-500" />
          </button>
        )}
        {onEdit && (
          <button
            className="p-1 rounded-full hover:bg-gray-100"
            onClick={onEdit}
            title={isEditing ? "Cancel Edit" : "Edit Slide"}
          >
            <Pencil className="h-4 w-4 text-blue-500" />
          </button>
        )}
        {isEditing && onSave && (
          <button
            className="p-1 rounded-full hover:bg-gray-100"
            onClick={onSave}
            title="Save Changes"
          >
            <Save className="h-4 w-4 text-green-500" />
          </button>
        )}
      </div>
      
      {isEditing ? (
        <>
          <input
            type="text"
            value={slide.title}
            onChange={(e) => onTitleChange && onTitleChange(e.target.value)}
            className="mb-3 text-lg font-medium text-center border border-gray-200 rounded-md p-1"
          />
          <textarea
            value={slide.content}
            onChange={(e) => onContentChange && onContentChange(e.target.value)}
            className="flex-1 m-0 text-base leading-relaxed border border-gray-200 rounded-md p-2 resize-none"
          />
        </>
      ) : (
        <>
          <h3 className="mb-3 text-lg font-medium">{slide.title}</h3>
          <p className="m-0 text-base leading-relaxed text-gray-600">{slide.content}</p>
        </>
      )}
      
      {showFooter && (
        <div className="mt-auto text-center mb-1">
          <p className="text-xs text-gray-400">{footerText}</p>
        </div>
      )}
    </div>
  );
};

export default SlideCard;
