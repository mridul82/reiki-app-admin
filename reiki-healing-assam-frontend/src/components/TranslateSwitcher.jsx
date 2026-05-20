import { useState } from "react"

const LANGS = [
  { code: "en", label: "EN" },
  { code: "hi", label: "हि" },
  { code: "as", label: "অ" },
]

function setGoogleTranslateLang(langCode) {
  if (langCode === "en") {
    document.cookie = "googtrans=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/"
    document.cookie = "googtrans=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/; domain=." + window.location.hostname
    window.location.reload()
    return
  }
  document.cookie = `googtrans=/en/${langCode}; path=/`
  document.cookie = `googtrans=/en/${langCode}; path=/; domain=.${window.location.hostname}`
  window.location.reload()
}

export default function TranslateSwitcher() {
  const getInitialLang = () => {
    const match = document.cookie.match(/googtrans=\/en\/([a-z]+)/)
    return match ? match[1] : "en"
  }
  const [active, setActive] = useState(getInitialLang)
  const handleLang = (code) => {
    if (code === active) return
    setActive(code)
    setGoogleTranslateLang(code)
  }
  return (
    <div className="flex items-center gap-1 shrink-0">
      {LANGS.map(l => (
        <button
          key={l.code}
          onClick={() => handleLang(l.code)}
          className="px-2.5 py-1 rounded-lg text-xs font-bold transition-all"
          style={{
            background: active === l.code ? "rgba(245,197,24,0.9)" : "rgba(255,255,255,0.1)",
            color: active === l.code ? "#1a0a00" : "rgba(245,197,24,0.7)",
            border: "1px solid rgba(245,197,24,0.35)",
          }}
        >
          {l.label}
        </button>
      ))}
    </div>
  )
}
