import {createContext, useContext, useState, useEffect} from "react"
import api from '../api/axios.ts'

interface AuthContextType {
    user: any,
    employee: any,
    loading: boolean,
    login: (email: string, password: string) => Promise<void>
}

const AuthContext = createContext<AuthContextType | null>(null)

export function AuthProvider({ children }: { children: React.ReactNode }) {
    const [user, setUser] = useState(null)
    const [employee, setEmployee] = useState(null)
    const [loading, setLoading] = useState(true)

    useEffect(() => {
        const token = localStorage.getItem('token')
        if (token) {
            api.get('/users/data')
                .then(response => {
                    setUser(response.data.data.user)
                    setEmployee(response.data.data.employee)
                })
                .catch(() => localStorage.removeItem('token'))
                .finally(() => setLoading(false))
        } else {
            setLoading(false)
        }
    }, [])

    async function login(email: string, password: string) {
        const response = await api.post('/auth/login', {email,password})
        localStorage.setItem('token', response.data.access_token)
        setUser(response.data.user_data)
        
        const employeeData = await api.get('/users/data')
        setEmployee(employeeData.data.data.employee)
    }

    return (
        <AuthContext.Provider value={{ user, employee, loading, login }}>
            {children}
        </AuthContext.Provider>
    )
}

export const useAuth = () => useContext(AuthContext)
