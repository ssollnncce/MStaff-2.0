import { use, useState } from 'react'
import { useAuth } from '../../context/AuthContext'
import { useNavigate } from 'react-router-dom'
import loginStyle from './Login.module.css'
import useTitle from '../../hooks/useTitle.tsx'
//Icons
import eyeIcon from '../../assets/icons/eye-icon.svg'

export default function Login() {
    useTitle('Login - Security Staff Management')
    const auth = useAuth()
    const navigate = useNavigate()
    const [email, setEmail] = useState('')
    const [password, setPassword] = useState('')
    const [error, setError] = useState<string[]>([])
    const [showPassword, setShowPassword] = useState(false)

    async function handleSubmit(e: React.FormEvent) {
        e.preventDefault()
        setError([])
        try {
            await auth!.login(email, password)
            navigate('/')
        } catch (error: any) {
            const errors = error?.response?.data?.errors
            if (errors) {
                setError(Object.values<string[]>(errors).flat())
            } else {
                setError(['Login failed. Please check your credentials and try again.'])
            }
        }
    }

    return (
        <div className={loginStyle['login-container']}>
            <div className={loginStyle['login-container__box']}>
                <div className={loginStyle['login-container__box-logo']}>
                    <p className={loginStyle['paragraph-title']}>
                        Security Staff Management
                    </p>
                    <p className={`${loginStyle['paragraph-title']} ${loginStyle['login__box-title']}`}>
                        Sign in to access your dashboard
                    </p>
                </div>
                    {error.length > 0 && error.map((msg, i) => (
                        <p key={i} className={loginStyle['error-message']}>{msg}</p>
                    ))}
                <form onSubmit={handleSubmit} className={loginStyle['login__box-form']}>
                    <div className={loginStyle['login__box-form-inputs']}>
                        <label htmlFor="email">Email</label>
                        <input type="email" 
                            placeholder='your.email@security.com' 
                            value={email} onChange={(e) => setEmail(e.target.value)} 
                            id='email'
                            className={loginStyle['login__box-form-input']}
                        />
                    </div>
                    <div className={loginStyle['login__box-form-inputs']}>
                        <label htmlFor="password">Password</label>
                        <div className={loginStyle['password-wrapper']}>
                            <input type={showPassword ? 'text' : 'password'}
                                placeholder='Enter your password'
                                value={password} onChange={(e) => setPassword(e.target.value)}
                                id='password'
                                className={loginStyle['login__box-form-input']}
                            />
                            <button type="button" onClick={() => setShowPassword(!showPassword)}>
                                <img src={eyeIcon}/>
                            </button>
                        </div>
                    </div>
                    <div className={loginStyle['login__box-form-inputs']}>
                        <button type="submit" className={loginStyle['login__box-form-button']}
                        onClick={handleSubmit}>
                            Sign In
                        </button>
                    </div>
                </form>
                <div className={loginStyle['login__box-demoCredentials']}>
                    <p className={loginStyle['paragraph-title-2']}>Demo Credentials</p>
                    <div className={loginStyle['login__box-demoCredentials__content']}>
                        <p className="secondary-text">Admin:</p>
                        <p className="secondary-text">Manager:</p>
                        <p className="secondary-text">Staff:</p>
                        <p className='secondary-text'>Password:</p>
                    </div>
                </div>
            </div>
        </div>
    )
}
