import { useAuth } from '../../context/AuthContext'
import useTitle from '../../hooks/useTitle.tsx'

export default function Dashboard() {
    const auth = useAuth()
    useTitle(`Dashboard - ${auth?.user?.first_name || 'Security Staff Management'}`)

    return (
        <div>
            <h1>Dashboard</h1>
            <p>Welcome, {auth?.user?.first_name || 'User'}!</p>
            {Array.from({ length: 100 }, (_, i) => (
                <p key={i}>This is line {i + 1}</p>
            ))}
        </div>
    )
}