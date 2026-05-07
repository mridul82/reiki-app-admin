import { createContext, useContext, useState, useEffect } from "react"
import api from "@/lib/api"

const AuthContext = createContext(null)

export function AuthProvider({ children }) {
  const [user, setUser] = useState(null)
  const [loading, setLoading] = useState(true)

  useEffect(() => {
    const token = localStorage.getItem("token")
    if (token) {
      api.defaults.headers.common["Authorization"] = `Bearer ${token}`
      api.get("/user")
        .then(res => setUser(res.data))
        .catch(() => {
          localStorage.removeItem("token")
          delete api.defaults.headers.common["Authorization"]
        })
        .finally(() => setLoading(false))
    } else {
      setLoading(false)
    }
  }, [])

  const login = async (email, password) => {
    const res = await api.post("/login", { email, password })
    const { token, user } = res.data
    localStorage.setItem("token", token)
    api.defaults.headers.common["Authorization"] = `Bearer ${token}`
    setUser(user)
    return user
  }

  const logout = async () => {
    await api.post("/logout").catch(() => {})
    localStorage.removeItem("token")
    delete api.defaults.headers.common["Authorization"]
    setUser(null)
  }

  return (
    <AuthContext.Provider value={{ user, loading, login, logout }}>
      {children}
    </AuthContext.Provider>
  )
}

export function useAuth() {
  return useContext(AuthContext)
}
