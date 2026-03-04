import navStyle from './Navigation.module.css'
import { useAuth } from '../../context/AuthContext'
import { NavLink, useLocation } from 'react-router-dom'

function Navigation() {

    const auth = useAuth()
    const location = useLocation()

    return (
        <div className={navStyle['nav']}>
            <div className={navStyle['nav-main']}>
                <div className={navStyle['logo']}>
                    <p className='paragraph-title'>Management Staff</p>
                    <p className='note'>Version 2.0</p>
                </div>
                <div className={navStyle['profile']}>
                    <div className={navStyle['profile-info']}>
                        <div className={navStyle['avatar-wrapper']}>
                            <div className={auth?.user?.photo_path ? navStyle['avatar'] : navStyle['avatar-placeholder']}>
                                {auth?.user?.photo_path ? <img src={auth.user.photo_path} alt="User Avatar" /> : <p className={navStyle['avatar-initials']}>{auth?.user?.first_name?.[0] || 'U'}{auth?.user?.last_name?.[0] || 'U'}</p>}
                            </div>
                        </div>
                        <div className={navStyle['username']}>
                            <p className={navStyle['username-text']}>
                                {auth?.user?.first_name|| 'User'} <br/> {auth?.user?.last_name || 'user'}
                            </p>
                        </div>
                    </div>
                </div>
                <div className={navStyle['menu']}>
                    <div className={navStyle['menu-label']}>
                        <p>MAIN MENU:</p>
                    </div>
                    <nav className={navStyle['menu-nav']}>
                        <ul className={navStyle['menu-list']}>
                            <li className={`${navStyle['menu-item']} ${location.pathname === '/' ? navStyle['active-link'] : navStyle['inactive-link']}`}><NavLink to="/">Dashboard</NavLink></li>
                            <li className={`${navStyle['menu-item']} ${location.pathname === '/projects' ? navStyle['active-link'] : navStyle['inactive-link']}`}><NavLink to="/projects">Projects management</NavLink></li>
                        </ul>
                    </nav>
                </div>
            </div>
            <div className={navStyle['footer']}>
                <p>SETTINGS</p>
                <nav>
                    <ul>
                        <li>Profile</li>
                        <li>Settings</li>
                        <li>Logout</li>
                    </ul>
                </nav>
            </div>
        </div>
    )
}

export default Navigation
