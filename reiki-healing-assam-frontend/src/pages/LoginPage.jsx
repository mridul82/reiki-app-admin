import { useState } from "react"
import { useNavigate } from "react-router-dom"
import { useAuth } from "@/context/AuthContext"

export default function LoginPage() {
  const { login } = useAuth()
  const navigate = useNavigate()
  const [email, setEmail] = useState("")
  const [password, setPassword] = useState("")
  const [error, setError] = useState("")
  const [loading, setLoading] = useState(false)

  const handleSubmit = async (e) => {
    e.preventDefault()
    setError("")
    setLoading(true)
    try {
      await login(email, password)
      navigate("/dashboard")
    } catch (err) {
      setError(err.response?.data?.message || "Invalid credentials")
    } finally {
      setLoading(false)
    }
  }

  return (
    <div className="min-h-screen flex items-center justify-center px-4"
      style={{ background: "linear-gradient(135deg, #1a0a00 0%, #3d1f00 40%, #7a4500 100%)" }}>

      {/* Decorative circles */}
      <div className="absolute inset-0 overflow-hidden pointer-events-none">
        <div className="absolute -top-32 -left-32 w-96 h-96 rounded-full opacity-10"
          style={{ background: "radial-gradient(circle, #f5c518, transparent)" }} />
        <div className="absolute -bottom-32 -right-32 w-96 h-96 rounded-full opacity-10"
          style={{ background: "radial-gradient(circle, #f5c518, transparent)" }} />
      </div>

      <div className="relative w-full max-w-md">
        {/* Logo / Brand */}
        <div className="text-center mb-8">
          <div className="inline-flex items-center justify-center w-20 h-20 rounded-full mb-4"
            style={{ background: "linear-gradient(135deg, #f5c518, #e6a800)" }}>
            <span className="text-3xl">☀</span>
          </div>
          <h1 className="text-3xl font-bold text-white tracking-wide">Reiki Healing Assam</h1>
          <p className="text-yellow-300/70 mt-1 text-sm">Premium Healing Platform</p>
        </div>

        {/* Card */}
        <div className="rounded-2xl shadow-2xl p-8"
          style={{ background: "rgba(255,255,255,0.06)", backdropFilter: "blur(16px)", border: "1px solid rgba(245,197,24,0.25)" }}>

          <h2 className="text-xl font-semibold text-white mb-6 text-center">Sign in to your account</h2>

          <form onSubmit={handleSubmit} className="space-y-5">
            <div>
              <label className="block text-sm font-medium text-yellow-200/80 mb-1.5">Email Address</label>
              <input
                type="email"
                value={email}
                onChange={e => setEmail(e.target.value)}
                placeholder="you@example.com"
                required
                className="w-full px-4 py-3 rounded-xl text-white placeholder-white/30 outline-none transition focus:ring-2"
                style={{
                  background: "rgba(255,255,255,0.08)",
                  border: "1px solid rgba(245,197,24,0.3)",
                  focusRing: "#f5c518"
                }}
                onFocus={e => e.target.style.borderColor = "#f5c518"}
                onBlur={e => e.target.style.borderColor = "rgba(245,197,24,0.3)"}
              />
            </div>

            <div>
              <label className="block text-sm font-medium text-yellow-200/80 mb-1.5">Password</label>
              <input
                type="password"
                value={password}
                onChange={e => setPassword(e.target.value)}
                placeholder="••••••••"
                required
                className="w-full px-4 py-3 rounded-xl text-white placeholder-white/30 outline-none transition"
                style={{ background: "rgba(255,255,255,0.08)", border: "1px solid rgba(245,197,24,0.3)" }}
                onFocus={e => e.target.style.borderColor = "#f5c518"}
                onBlur={e => e.target.style.borderColor = "rgba(245,197,24,0.3)"}
              />
            </div>

            {error && (
              <div className="flex items-center gap-2 bg-red-500/20 border border-red-400/30 rounded-lg px-4 py-3">
                <span className="text-red-300 text-sm">⚠ {error}</span>
              </div>
            )}

            <button
              type="submit"
              disabled={loading}
              className="w-full py-3 rounded-xl font-semibold text-base transition-all active:scale-95 disabled:opacity-60"
              style={{ background: "linear-gradient(135deg, #f5c518, #e6a800)", color: "#1a0a00" }}
            >
              {loading ? "Signing in…" : "Sign In"}
            </button>
          </form>
        </div>

        <p className="text-center text-yellow-200/30 text-xs mt-6">
          Developed by{" "}
          <a href="https://itnex-solutions.netlify.app/" target="_blank" rel="noopener noreferrer" className="underline text-yellow-200/50 hover:text-yellow-200/80">ITNext Solutions</a>
        </p>
      </div>
    </div>
  )
}
