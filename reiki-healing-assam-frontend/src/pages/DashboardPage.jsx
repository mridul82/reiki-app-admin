import { useState, useEffect, useRef, useCallback } from "react"
import { useAuth } from "@/context/AuthContext"
import { useNavigate } from "react-router-dom"
import api from "@/lib/api"

const REMEDY_COLORS = {
  "Crystal":           { badge: "#16a34a", bg: "#f0fdf4", icon: "💎" },
  "Lal Kitab":         { badge: "#ea580c", bg: "#fff7ed", icon: "📕" },
  "Switch Word":       { badge: "#9333ea", bg: "#fdf4ff", icon: "🔮" },
  "Vedic Switch Word": { badge: "#ca8a04", bg: "#fefce8", icon: "🕉" },
}

const CATEGORY_ICONS = {
  health: "🌿",
  relationship: "💛",
  career: "🚀",
  money: "💰",
}

function getCategoryIcon(name) {
  return CATEGORY_ICONS[name?.toLowerCase()] || "✨"
}

function formatDate(str) {
  if (!str) return "—"
  return new Date(str).toLocaleDateString("en-IN", { day: "2-digit", month: "short", year: "numeric" })
}

export default function DashboardPage() {
  const { user, logout } = useAuth()
  const navigate = useNavigate()
  const [categories, setCategories] = useState([])
  const [reports, setReports] = useState([])
  const [loadingCats, setLoadingCats] = useState(true)
  const [loadingReports, setLoadingReports] = useState(true)
  const [downloading, setDownloading] = useState(null)
  const [searchQuery, setSearchQuery] = useState("")
  const [searchResults, setSearchResults] = useState([])
  const [searching, setSearching] = useState(false)
  const [searchDone, setSearchDone] = useState(false)
  const [showDropdown, setShowDropdown] = useState(false)
  const searchTimer = useRef(null)
  const searchRef = useRef(null)

  useEffect(() => {
    const handleClickOutside = (e) => {
      if (searchRef.current && !searchRef.current.contains(e.target)) {
        setShowDropdown(false)
      }
    }
    document.addEventListener("mousedown", handleClickOutside)
    return () => document.removeEventListener("mousedown", handleClickOutside)
  }, [])

  const handleSearch = useCallback((q) => {
    setSearchQuery(q)
    clearTimeout(searchTimer.current)
    if (q.trim().length < 2) {
      setSearchResults([])
      setSearchDone(false)
      setShowDropdown(false)
      return
    }
    searchTimer.current = setTimeout(async () => {
      setSearching(true)
      setSearchDone(false)
      try {
        const res = await api.get("/solutions/search", { params: { q: q.trim() } })
        setSearchResults(res.data)
        setShowDropdown(true)
      } catch {
        setSearchResults([])
      } finally {
        setSearching(false)
        setSearchDone(true)
      }
    }, 400)
  }, [])

  const handleSearchSelect = useCallback((sol) => {
    const categoryId = sol.subcategory?.category_id
    const subcategoryId = sol.subcategory_id
    if (!categoryId) return
    setShowDropdown(false)
    setSearchQuery("")
    navigate(`/categories/${categoryId}`, { state: { preselectedSubcategories: [subcategoryId] } })
  }, [navigate])

  useEffect(() => {
    api.get("/categories").then(res => setCategories(res.data)).finally(() => setLoadingCats(false))
    api.get("/reports").then(res => setReports(res.data)).finally(() => setLoadingReports(false))
  }, [])

  const handleDownload = async (report) => {
    setDownloading(report.id)
    try {
      const res = await api.get(`/reports/${report.id}/download`, {
        responseType: "blob",
        headers: { Accept: "application/pdf" },
      })

      // If server returned an error, the blob will be JSON — detect and show it
      if (res.data.type === "application/json") {
        const text = await res.data.text()
        const json = JSON.parse(text)
        alert(json.message || "Failed to download report.")
        return
      }

      const url = window.URL.createObjectURL(new Blob([res.data], { type: "application/pdf" }))
      const a = document.createElement("a")
      a.href = url
      a.download = `report-${report.customer_first_name}-${report.customer_last_name}.pdf`
      a.click()
      window.URL.revokeObjectURL(url)
    } catch (err) {
      // Try to read error message from blob if available
      if (err.response?.data instanceof Blob) {
        const text = await err.response.data.text()
        try {
          const json = JSON.parse(text)
          alert(json.message || "Failed to download report.")
        } catch {
          alert("Failed to download report. Please try again.")
        }
      } else {
        alert(err.response?.data?.message || "Failed to download report. Please try again.")
      }
    } finally {
      setDownloading(null)
    }
  }

  const handleLogout = async () => {
    await logout()
    navigate("/login")
  }

  return (
    <div className="min-h-screen" style={{ background: "linear-gradient(160deg, #fdf8ed 0%, #fef3c7 50%, #fdf8ed 100%)" }}>

      {/* Header */}
      <header className="sticky top-0 z-10 shadow-sm"
        style={{ background: "linear-gradient(90deg, #1a0a00, #3d1f00)", borderBottom: "2px solid #f5c518" }}>
        <div className="max-w-6xl mx-auto px-6 py-4 flex items-center gap-4">
          {/* Logo */}
          <div className="flex items-center gap-3 shrink-0">
            <div className="w-10 h-10 rounded-full flex items-center justify-center text-lg"
              style={{ background: "linear-gradient(135deg, #f5c518, #e6a800)" }}>☀</div>
            <div className="hidden sm:block">
              <h1 className="text-lg font-bold text-white leading-tight">Reiki Healing Assam</h1>
              <p className="text-yellow-300/60 text-xs">Premium Healing Platform</p>
            </div>
          </div>

          {/* Search bar */}
          <div className="flex-1 relative" ref={searchRef}>
            <span className="absolute left-3 top-1/2 -translate-y-1/2 text-sm" style={{ color: "#b8956a" }}>🔍</span>
            <input
              type="text"
              value={searchQuery}
              onChange={e => handleSearch(e.target.value)}
              onFocus={() => searchQuery.trim().length >= 2 && setShowDropdown(true)}
              placeholder="Search solutions by keyword…"
              className="w-full pl-9 pr-4 py-2 rounded-xl text-sm outline-none"
              style={{
                background: "rgba(255,255,255,0.1)",
                border: "1px solid rgba(245,197,24,0.35)",
                color: "white",
              }}
            />
            {searching && (
              <span className="absolute right-3 top-1/2 -translate-y-1/2 animate-spin text-sm" style={{ color: "#f5c518" }}>⟳</span>
            )}

            {/* Dropdown results */}
            {showDropdown && (
              <div className="absolute top-full left-0 right-0 mt-2 rounded-xl overflow-hidden shadow-2xl z-50"
                style={{ background: "white", border: "1px solid rgba(245,197,24,0.4)", maxHeight: "360px", overflowY: "auto" }}>
                {searchResults.length === 0 && searchDone ? (
                  <div className="px-4 py-6 text-center">
                    <p className="text-sm" style={{ color: "#7a5c2e" }}>No solutions found for "{searchQuery}"</p>
                  </div>
                ) : (
                  <>
                    <div className="px-4 py-2 text-xs font-bold uppercase tracking-wide"
                      style={{ background: "linear-gradient(90deg,#1a0a00,#3d1f00)", color: "rgba(245,197,24,0.7)", borderBottom: "1px solid rgba(245,197,24,0.2)" }}>
                      {searchResults.length} result{searchResults.length !== 1 ? "s" : ""}
                    </div>
                    {searchResults.map(sol => {
                      const remedy = REMEDY_COLORS[sol.remedy_type] || { badge: "#6b7280", bg: "#f9fafb", icon: "✨" }
                      return (
                        <button
                          key={sol.id}
                          onClick={() => handleSearchSelect(sol)}
                          className="w-full text-left px-4 py-3 flex items-start gap-3 transition-colors hover:bg-yellow-50"
                          style={{ borderBottom: "1px solid rgba(245,197,24,0.1)" }}>
                          <span className="text-base shrink-0 mt-0.5">{remedy.icon}</span>
                          <div className="min-w-0">
                            <p className="text-sm font-semibold truncate" style={{ color: "#1a0a00" }}>{sol.title}</p>
                            <div className="flex items-center gap-2 mt-0.5 flex-wrap">
                              {sol.subcategory && (
                                <span className="text-xs px-2 py-0.5 rounded-full font-medium"
                                  style={{ background: "rgba(245,197,24,0.2)", color: "#92620a" }}>
                                  {sol.subcategory.name}
                                </span>
                              )}
                              <span className="text-xs px-2 py-0.5 rounded-full font-medium text-white"
                                style={{ background: remedy.badge }}>
                                {sol.remedy_type}
                              </span>
                            </div>
                          </div>
                        </button>
                      )
                    })}
                  </>
                )}
              </div>
            )}
          </div>

          {/* User + Sign out */}
          <div className="flex items-center gap-3 shrink-0">
            <div className="text-right hidden md:block">
              <p className="text-white text-sm font-medium">{user?.name}</p>
              <p className="text-yellow-300/60 text-xs">{user?.email}</p>
            </div>
            <button onClick={handleLogout}
              className="px-4 py-2 rounded-lg text-sm font-medium transition-all hover:opacity-90"
              style={{ background: "rgba(245,197,24,0.15)", border: "1px solid rgba(245,197,24,0.4)", color: "#f5c518" }}>
              Sign Out
            </button>
          </div>
        </div>
      </header>

      <div className="max-w-6xl mx-auto px-6 pt-12 pb-16 space-y-14">

        {/* ── Categories ── */}
        <section>
          <div className="text-center mb-8">
            <div className="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-sm font-medium mb-4"
              style={{ background: "rgba(245,197,24,0.15)", border: "1px solid rgba(245,197,24,0.4)", color: "#92620a" }}>
              ✦ Select a Life Area
            </div>
            <h2 className="text-3xl font-bold mb-3" style={{ color: "#1a0a00" }}>Generate a Healing Report</h2>
            <p className="text-base max-w-lg mx-auto" style={{ color: "#7a5c2e" }}>
              Choose a category below to enter your customer's details and generate a personalised report.
            </p>
          </div>

          {loadingCats ? (
            <div className="flex items-center justify-center py-16">
              <div className="text-center">
                <div className="text-4xl mb-3 animate-pulse">☀</div>
                <p style={{ color: "#92620a" }}>Loading categories…</p>
              </div>
            </div>
          ) : categories.length === 0 ? (
            <div className="text-center py-16">
              <div className="text-5xl mb-4">🌿</div>
              <p style={{ color: "#92620a" }}>No categories available yet.</p>
            </div>
          ) : (
            <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
              {categories.map(cat => (
                <button key={cat.id} onClick={() => navigate(`/categories/${cat.id}`)}
                  className="group text-left rounded-2xl p-6 transition-all duration-200 hover:-translate-y-1"
                  style={{
                    background: "white",
                    border: "2px solid transparent",
                    boxShadow: "0 2px 12px rgba(245,197,24,0.12)",
                    backgroundImage: "linear-gradient(white, white), linear-gradient(135deg, #f5c518, #e6a800)",
                    backgroundOrigin: "border-box",
                    backgroundClip: "padding-box, border-box",
                  }}
                  onMouseEnter={e => e.currentTarget.style.boxShadow = "0 8px 30px rgba(245,197,24,0.3)"}
                  onMouseLeave={e => e.currentTarget.style.boxShadow = "0 2px 12px rgba(245,197,24,0.12)"}
                >
                  <div className="w-14 h-14 rounded-xl flex items-center justify-center text-2xl mb-4 transition-transform group-hover:scale-110"
                    style={{ background: "linear-gradient(135deg, #fef3c7, #fde68a)" }}>
                    {getCategoryIcon(cat.name)}
                  </div>
                  <h3 className="text-lg font-bold mb-1" style={{ color: "#1a0a00" }}>{cat.name}</h3>
                  {cat.description && (
                    <p className="text-sm leading-relaxed" style={{ color: "#7a5c2e" }}>{cat.description}</p>
                  )}
                  <div className="mt-4 flex items-center gap-1 text-xs font-semibold" style={{ color: "#e6a800" }}>
                    Generate Report <span className="transition-transform group-hover:translate-x-1">→</span>
                  </div>
                </button>
              ))}
            </div>
          )}
        </section>

        {/* ── Reports History ── */}
        <section>
          <div className="flex items-center justify-between mb-6">
            <div>
              <h2 className="text-2xl font-bold" style={{ color: "#1a0a00" }}>Generated Reports</h2>
              <p className="text-sm mt-1" style={{ color: "#7a5c2e" }}>All reports you have generated for your customers.</p>
            </div>
            {reports.length > 0 && (
              <span className="px-3 py-1 rounded-full text-sm font-bold"
                style={{ background: "rgba(245,197,24,0.2)", border: "1px solid rgba(245,197,24,0.4)", color: "#92620a" }}>
                {reports.length} total
              </span>
            )}
          </div>

          {loadingReports ? (
            <div className="text-center py-10">
              <div className="text-3xl animate-pulse mb-2">✦</div>
              <p className="text-sm" style={{ color: "#92620a" }}>Loading reports…</p>
            </div>
          ) : reports.length === 0 ? (
            <div className="rounded-2xl p-10 text-center"
              style={{ background: "white", border: "1px dashed rgba(245,197,24,0.5)" }}>
              <div className="text-4xl mb-3">📄</div>
              <p className="font-semibold" style={{ color: "#3d1f00" }}>No reports yet</p>
              <p className="text-sm mt-1" style={{ color: "#7a5c2e" }}>Generate your first report by selecting a category above.</p>
            </div>
          ) : (
            <div className="rounded-2xl overflow-hidden shadow-sm"
              style={{ border: "1px solid rgba(245,197,24,0.3)", background: "white" }}>
              {/* Table header */}
              <div className="grid grid-cols-12 px-5 py-3 text-xs font-bold uppercase tracking-wide"
                style={{ background: "linear-gradient(90deg, #1a0a00, #3d1f00)", color: "rgba(245,197,24,0.7)", borderBottom: "2px solid #f5c518" }}>
                <div className="col-span-4">Customer</div>
                <div className="col-span-2">Date of Birth</div>
                <div className="col-span-2">Contact</div>
                <div className="col-span-2">Category</div>
                <div className="col-span-1">Generated On</div>
                <div className="col-span-1 text-center">PDF</div>
              </div>

              {/* Rows */}
              <div className="divide-y" style={{ borderColor: "rgba(245,197,24,0.15)" }}>
                {reports.map((report, i) => (
                  <div key={report.id}
                    className="grid grid-cols-12 px-5 py-4 items-center transition-colors hover:bg-yellow-50/50"
                    style={{ background: i % 2 === 0 ? "white" : "#fffdf8" }}>
                    <div className="col-span-4 flex items-center gap-3">
                      <div className="w-9 h-9 rounded-full flex items-center justify-center text-sm font-bold shrink-0"
                        style={{ background: "linear-gradient(135deg, #fef3c7, #fde68a)", color: "#92620a" }}>
                        {report.customer_first_name?.[0]}{report.customer_last_name?.[0]}
                      </div>
                      <div>
                        <p className="font-semibold text-sm" style={{ color: "#1a0a00" }}>
                          {report.customer_first_name} {report.customer_last_name}
                        </p>
                      </div>
                    </div>
                    <div className="col-span-2 text-sm" style={{ color: "#7a5c2e" }}>
                      {formatDate(report.customer_dob)}
                    </div>
                    <div className="col-span-2 text-sm" style={{ color: "#7a5c2e" }}>
                      {report.customer_contact}
                    </div>
                    <div className="col-span-2">
                      <span className="px-2.5 py-1 rounded-full text-xs font-bold"
                        style={{ background: "rgba(245,197,24,0.2)", color: "#92620a", border: "1px solid rgba(245,197,24,0.4)" }}>
                        {report.module}
                      </span>
                    </div>
                    <div className="col-span-1 text-xs" style={{ color: "#b8956a" }}>
                      {formatDate(report.created_at)}
                    </div>
                    <div className="col-span-1 flex justify-center">
                      <button
                        onClick={() => handleDownload(report)}
                        disabled={downloading === report.id}
                        title="Download PDF"
                        className="w-8 h-8 rounded-lg flex items-center justify-center transition-all hover:scale-110 disabled:opacity-50"
                        style={{ background: "linear-gradient(135deg, #f5c518, #e6a800)", color: "#1a0a00" }}
                      >
                        {downloading === report.id ? (
                          <span className="text-xs animate-spin">⟳</span>
                        ) : (
                          <span className="text-xs font-bold">⬇</span>
                        )}
                      </button>
                    </div>
                  </div>
                ))}
              </div>
            </div>
          )}
        </section>

      </div>

      <footer className="text-center py-6">
        <p className="text-xs" style={{ color: "#b8956a" }}>
          © {new Date().getFullYear()} Reiki Healing Assam &mdash; Developed by{" "}
          <a href="https://itnex-solutions.netlify.app/" target="_blank" rel="noopener noreferrer" style={{ color: "#d4a843", textDecoration: "underline" }}>ITNext Solutions</a>
        </p>
      </footer>
    </div>
  )
}
