import {Navigate} from "react-router-dom"
import { useAuth } from "../context/AuthContext.tsx"

export default function GuestRoute({ children }: { children: React.ReactNode }) {
    const auth = useAuth()

    if (auth?.user) {
        return <Navigate to="/" />
    }
    return children
}