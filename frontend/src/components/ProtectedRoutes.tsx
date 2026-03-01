import {Navigate} from "react-router-dom"
import { useAuth } from "../context/AuthContext.tsx"

export default function ProtectedRoutes({ children }: { children: React.ReactNode }) {
    const auth = useAuth()

    if (!auth || !auth.user) return <Navigate to="/login" />

    return children
}