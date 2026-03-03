import { useAuth } from '../../context/AuthContext'
import useTitle from '../../hooks/useTitle.tsx'

export default function Dashboard() {
    const auth = useAuth()
    useTitle(`Dashboard - ${auth?.user?.first_name || 'Security Staff Management'}`)

    return (
        <p className='paragraph-title'>Dashboard</p>
    )
}