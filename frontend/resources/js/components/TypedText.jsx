import React, { useEffect, useRef } from 'react';
import Typed from 'typed.js';

export default function TypedText() {
  const el = useRef(null);

  useEffect(() => {
    // 1. Define your strings and settings here:
    const options = {
      strings: ["Create with One Click", "Publish Instantly", "Powered By Universal Technologies"],
      typeSpeed: 80,      // typing speed (ms per char)
      backSpeed: 40,      // backspace speed
      backDelay: 3000,    // pause before backspacing
      startDelay: 500,    // pause before typing first string
      loop: true,         // keep cycling
      showCursor: true,   // display the cursor
      cursorChar: '|'     // customize cursor character
    };

    // 2. Initialize Typed.js on our <span>:
    const typed = new Typed(el.current, options);

    // 3. Cleanup on unmount:
    return () => typed.destroy();
  }, []);

  // This <span> is the target for typing:
  return <span ref={el} className="text-gradient-1 mt-10" />;
}
