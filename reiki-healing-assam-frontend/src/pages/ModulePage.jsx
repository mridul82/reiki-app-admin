import { useState, useEffect } from "react"
import { useParams, useNavigate, useLocation } from "react-router-dom"
import api from "@/lib/api"

const REMEDY_COLORS = {
  "Crystal":           { bg: "#f0fdf4", border: "#86efac", badge: "#16a34a", text: "#14532d", icon: "💎" },
  "Lal Kitab":         { bg: "#fff7ed", border: "#fdba74", badge: "#ea580c", text: "#7c2d12", icon: "📕" },
  "Switch Word":       { bg: "#fdf4ff", border: "#d8b4fe", badge: "#9333ea", text: "#581c87", icon: "🔮" },
  "Vedic Switch Word": { bg: "#fefce8", border: "#fde047", badge: "#ca8a04", text: "#713f12", icon: "🕉" },
}

function RemedyBadge({ type }) {
  const c = REMEDY_COLORS[type] || { bg: "#f9fafb", border: "#d1d5db", badge: "#6b7280", text: "#374151", icon: "✦" }
  return (
    <span className="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold"
      style={{ background: c.badge, color: "white" }}>
      {c.icon} {type}
    </span>
  )
}

// ─── Step 1: Form ────────────────────────────────────────────────────────────
function StepForm({ subcategories, fetching, onPreview, preselected = [] }) {
  const navigate = useNavigate()
  const [selected, setSelected] = useState(preselected)
  const [customer, setCustomer] = useState({ first_name: "", last_name: "", dob: "", contact: "" })
  const [previewing, setPreviewing] = useState(false)

  const toggleSub = (id) =>
    setSelected(prev => prev.includes(id) ? prev.filter(s => s !== id) : [...prev, id])

  const handleSubmit = async (e) => {
    e.preventDefault()
    if (selected.length === 0) return alert("Please select at least one subcategory.")
    setPreviewing(true)
    try {
      const res = await api.post("/subcategories/solutions", { subcategory_ids: selected })
      onPreview({ customer, selected, subcategoriesWithSolutions: res.data })
    } catch {
      alert("Failed to load remedies. Please try again.")
    } finally {
      setPreviewing(false)
    }
  }

  return (
    <form onSubmit={handleSubmit} className="space-y-8">

      {/* Customer Details */}
      <section className="rounded-2xl overflow-hidden shadow-sm"
        style={{ border: "1px solid rgba(245,197,24,0.3)", background: "white" }}>
        <div className="px-6 py-4 flex items-center gap-3"
          style={{ background: "linear-gradient(90deg, #1a0a00, #3d1f00)", borderBottom: "2px solid #f5c518" }}>
          <span className="text-xl">👤</span>
          <h2 className="text-base font-bold text-white">Customer Details</h2>
        </div>
        <div className="p-6 grid grid-cols-1 sm:grid-cols-2 gap-5">
          {[
            { label: "First Name", key: "first_name", type: "text", placeholder: "Enter first name" },
            { label: "Last Name",  key: "last_name",  type: "text", placeholder: "Enter last name" },
            { label: "Date of Birth", key: "dob",     type: "date", placeholder: "" },
            { label: "Contact Number", key: "contact", type: "text", placeholder: "Enter contact number" },
          ].map(field => (
            <div key={field.key}>
              <label className="block text-sm font-semibold mb-1.5" style={{ color: "#3d1f00" }}>
                {field.label}
              </label>
              <input
                type={field.type}
                placeholder={field.placeholder}
                value={customer[field.key]}
                onChange={e => setCustomer(p => ({ ...p, [field.key]: e.target.value }))}
                required
                className="w-full px-4 py-2.5 rounded-xl outline-none transition"
                style={{ border: "1.5px solid #e6c97a", background: "#fffdf5", color: "#1a0a00" }}
                onFocus={e => e.target.style.borderColor = "#f5c518"}
                onBlur={e => e.target.style.borderColor = "#e6c97a"}
              />
            </div>
          ))}
        </div>
      </section>

      {/* Subcategory Selection */}
      <section className="rounded-2xl overflow-hidden shadow-sm"
        style={{ border: "1px solid rgba(245,197,24,0.3)", background: "white" }}>
        <div className="px-6 py-4 flex items-center justify-between"
          style={{ background: "linear-gradient(90deg, #1a0a00, #3d1f00)", borderBottom: "2px solid #f5c518" }}>
          <div className="flex items-center gap-3">
            <span className="text-xl">✦</span>
            <h2 className="text-base font-bold text-white">Select Problem Areas</h2>
          </div>
          {selected.length > 0 && (
            <span className="px-2.5 py-0.5 rounded-full text-xs font-bold"
              style={{ background: "#f5c518", color: "#1a0a00" }}>
              {selected.length} selected
            </span>
          )}
        </div>
        <div className="p-6">
          {fetching ? (
            <div className="text-center py-8">
              <div className="text-3xl mb-2 animate-pulse">✦</div>
              <p className="text-sm" style={{ color: "#92620a" }}>Loading subcategories…</p>
            </div>
          ) : subcategories.length === 0 ? (
            <p className="text-center py-6 text-sm" style={{ color: "#92620a" }}>No subcategories available.</p>
          ) : (
            <div className="grid grid-cols-1 sm:grid-cols-2 gap-3">
              {subcategories.map(sub => {
                const isSelected = selected.includes(sub.id)
                return (
                  <label key={sub.id}
                    className="flex items-start gap-3 p-4 rounded-xl cursor-pointer transition-all"
                    style={{
                      border: isSelected ? "2px solid #f5c518" : "1.5px solid #e6c97a",
                      background: isSelected ? "linear-gradient(135deg, #fffbeb, #fef9d9)" : "#fffdf5",
                      boxShadow: isSelected ? "0 2px 12px rgba(245,197,24,0.2)" : "none",
                    }}>
                    <div className="relative mt-0.5 shrink-0">
                      <input type="checkbox" checked={isSelected} onChange={() => toggleSub(sub.id)} className="sr-only" />
                      <div className="w-5 h-5 rounded-md flex items-center justify-center transition-all"
                        style={{
                          background: isSelected ? "linear-gradient(135deg, #f5c518, #e6a800)" : "white",
                          border: isSelected ? "none" : "2px solid #e6c97a",
                        }}>
                        {isSelected && <span className="text-xs font-bold" style={{ color: "#1a0a00" }}>✓</span>}
                      </div>
                    </div>
                    <div>
                      <p className="text-sm font-semibold" style={{ color: "#1a0a00" }}>{sub.name}</p>
                      {sub.description && (
                        <p className="text-xs mt-0.5" style={{ color: "#7a5c2e" }}>{sub.description}</p>
                      )}
                    </div>
                  </label>
                )
              })}
            </div>
          )}
        </div>
      </section>

      <button type="submit" disabled={previewing}
        className="w-full py-4 rounded-2xl text-base font-bold transition-all active:scale-95 disabled:opacity-60 shadow-lg"
        style={{ background: "linear-gradient(135deg, #f5c518, #e6a800)", color: "#1a0a00", boxShadow: "0 4px 20px rgba(245,197,24,0.4)" }}>
        {previewing ? (
          <span className="flex items-center justify-center gap-2"><span className="animate-spin">⟳</span> Loading Remedies…</span>
        ) : "View Remedies →"}
      </button>
    </form>
  )
}

// ─── Step 2: Remedy Preview ───────────────────────────────────────────────────
function StepPreview({ customer, selected, subcategoriesWithSolutions, categoryId, onBack }) {
  const [loading, setLoading] = useState(false)

  const handleDownload = async () => {
    setLoading(true)
    try {
      const res = await api.post("/reports/generate", {
        category_id: categoryId,
        subcategory_ids: selected,
        customer,
      }, { responseType: "blob" })

      const url = window.URL.createObjectURL(new Blob([res.data], { type: "application/pdf" }))
      const a = document.createElement("a")
      a.href = url
      a.download = `report-${customer.first_name}-${customer.last_name}.pdf`
      a.click()
      window.URL.revokeObjectURL(url)
    } catch {
      alert("Failed to generate report. Please try again.")
    } finally {
      setLoading(false)
    }
  }

  return (
    <div className="space-y-6">

      {/* Customer summary strip */}
      <div className="rounded-2xl px-6 py-4 flex flex-wrap items-center gap-4"
        style={{ background: "white", border: "1px solid rgba(245,197,24,0.35)" }}>
        <div className="w-10 h-10 rounded-full flex items-center justify-center text-lg shrink-0"
          style={{ background: "linear-gradient(135deg, #fef3c7, #fde68a)" }}>👤</div>
        <div className="flex-1 min-w-0">
          <p className="font-bold" style={{ color: "#1a0a00" }}>
            {customer.first_name} {customer.last_name}
          </p>
          <p className="text-xs" style={{ color: "#7a5c2e" }}>
            DOB: {customer.dob} &nbsp;·&nbsp; {customer.contact}
          </p>
        </div>
        <button onClick={onBack}
          className="px-4 py-2 rounded-lg text-sm font-medium transition-all shrink-0"
          style={{ background: "rgba(245,197,24,0.12)", border: "1px solid rgba(245,197,24,0.4)", color: "#92620a" }}>
          ← Edit Details
        </button>
      </div>

      {/* Remedies per subcategory */}
      {subcategoriesWithSolutions.map(sub => (
        <section key={sub.id} className="rounded-2xl overflow-hidden shadow-sm"
          style={{ border: "1px solid rgba(245,197,24,0.3)", background: "white" }}>
          <div className="px-6 py-4"
            style={{ background: "linear-gradient(90deg, #1a0a00, #3d1f00)", borderBottom: "2px solid #f5c518" }}>
            <h3 className="text-base font-bold text-white">{sub.name}</h3>
            <p className="text-yellow-300/50 text-xs mt-0.5">
              {sub.solutions.length} {sub.solutions.length === 1 ? "remedy" : "remedies"}
            </p>
          </div>

          {sub.solutions.length === 0 ? (
            <p className="px-6 py-5 text-sm" style={{ color: "#92620a" }}>No remedies added yet.</p>
          ) : (
            <div className="divide-y" style={{ borderColor: "rgba(245,197,24,0.15)" }}>
              {/* Group by remedy_type */}
              {Object.entries(
                sub.solutions.reduce((acc, sol) => {
                  if (!acc[sol.remedy_type]) acc[sol.remedy_type] = []
                  acc[sol.remedy_type].push(sol)
                  return acc
                }, {})
              ).map(([remedyType, solutions]) => {
                const c = REMEDY_COLORS[remedyType] || { bg: "#f9fafb", border: "#e5e7eb", badge: "#6b7280", text: "#374151", icon: "✦" }
                return (
                  <div key={remedyType} className="p-5">
                    <div className="flex items-center gap-2 mb-4">
                      <RemedyBadge type={remedyType} />
                      <span className="text-xs" style={{ color: "#b8956a" }}>{solutions.length} solution{solutions.length > 1 ? "s" : ""}</span>
                    </div>
                    <div className="space-y-3">
                      {solutions.map(sol => (
                        <div key={sol.id} className="rounded-xl p-4 flex gap-4"
                          style={{ background: c.bg, border: `1px solid ${c.border}` }}>
                          {sol.image_path && (
                            <img
                              src={`${import.meta.env.VITE_STORAGE_URL}/uploads/${sol.image_path}`}
                              alt={sol.title}
                              className="w-16 h-16 rounded-lg object-cover shrink-0"
                            />
                          )}
                          <div className="min-w-0 w-full">
                            <p className="font-bold text-lg mb-2" style={{ color: c.text }}>{sol.title}</p>
                            <div className="text-base font-semibold leading-relaxed rich-content"
                              style={{ color: c.text, opacity: 0.85 }}
                              dangerouslySetInnerHTML={{ __html: sol.content }}
                            />
                          </div>
                        </div>
                      ))}
                    </div>
                  </div>
                )
              })}
            </div>
          )}
        </section>
      ))}

      {/* Download Button */}
      <button onClick={handleDownload} disabled={loading}
        className="w-full py-4 rounded-2xl text-base font-bold transition-all active:scale-95 disabled:opacity-60 shadow-lg"
        style={{ background: "linear-gradient(135deg, #f5c518, #e6a800)", color: "#1a0a00", boxShadow: "0 4px 20px rgba(245,197,24,0.4)" }}>
        {loading ? (
          <span className="flex items-center justify-center gap-2"><span className="animate-spin">⟳</span> Generating PDF…</span>
        ) : "⬇ Download PDF Report"}
      </button>

    </div>
  )
}

// ─── Main Page ────────────────────────────────────────────────────────────────
export default function ModulePage() {
  const { categoryId } = useParams()
  const navigate = useNavigate()
  const location = useLocation()
  const preselected = location.state?.preselectedSubcategories ?? []
  const [category, setCategory] = useState(null)
  const [subcategories, setSubcategories] = useState([])
  const [fetching, setFetching] = useState(true)
  const [preview, setPreview] = useState(null) // null = step 1, object = step 2

  useEffect(() => {
    api.get(`/categories/${categoryId}/subcategories`)
      .then(res => {
        setSubcategories(res.data)
        if (res.data.length > 0) setCategory(res.data[0].category)
      })
      .finally(() => setFetching(false))
  }, [categoryId])

  const step = preview ? 2 : 1

  return (
    <div className="min-h-screen" style={{ background: "linear-gradient(160deg, #fdf8ed 0%, #fef3c7 50%, #fdf8ed 100%)" }}>

      {/* Header */}
      <header className="sticky top-0 z-10 shadow-sm"
        style={{ background: "linear-gradient(90deg, #1a0a00, #3d1f00)", borderBottom: "2px solid #f5c518" }}>
        <div className="max-w-4xl mx-auto px-6 py-4 flex items-center gap-4">
          <button
            onClick={() => step === 2 ? setPreview(null) : navigate("/dashboard")}
            className="flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium transition-all"
            style={{ background: "rgba(245,197,24,0.12)", border: "1px solid rgba(245,197,24,0.3)", color: "#f5c518" }}>
            ← {step === 2 ? "Back to Form" : "Back"}
          </button>
          <div className="flex-1 min-w-0">
            <h1 className="text-lg font-bold text-white leading-tight truncate">
              {category ? category.name : "Loading…"} — Report
            </h1>
            <p className="text-yellow-300/50 text-xs">
              {step === 1 ? "Fill details & select problem areas" : "Review remedies & download PDF"}
            </p>
          </div>
          {/* Step indicator */}
          <div className="hidden sm:flex items-center gap-2 text-xs">
            <span className="px-2.5 py-1 rounded-full font-bold"
              style={{ background: step === 1 ? "#f5c518" : "rgba(245,197,24,0.2)", color: step === 1 ? "#1a0a00" : "#f5c518" }}>
              1 Details
            </span>
            <span style={{ color: "rgba(245,197,24,0.4)" }}>›</span>
            <span className="px-2.5 py-1 rounded-full font-bold"
              style={{ background: step === 2 ? "#f5c518" : "rgba(245,197,24,0.2)", color: step === 2 ? "#1a0a00" : "rgba(245,197,24,0.4)" }}>
              2 Remedies
            </span>
          </div>
        </div>
      </header>

      <main className="max-w-4xl mx-auto px-6 py-10">
        {step === 1 ? (
          <StepForm
            subcategories={subcategories}
            fetching={fetching}
            onPreview={setPreview}
            preselected={preselected}
          />
        ) : (
          <StepPreview
            customer={preview.customer}
            selected={preview.selected}
            subcategoriesWithSolutions={preview.subcategoriesWithSolutions}
            categoryId={categoryId}
            onBack={() => setPreview(null)}
          />
        )}
      </main>
    </div>
  )
}
