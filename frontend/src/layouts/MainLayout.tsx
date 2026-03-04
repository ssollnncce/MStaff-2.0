import Navigation from '../components/Navigation/Navigation'
import { Outlet } from 'react-router-dom'

import MainLayoutStyle from './MainLayouts.module.css'

export default function MainLayout() {
    return (
        <div className={MainLayoutStyle['layout-container']}>
            <Navigation />
            <div className={MainLayoutStyle['content']}>
                <Outlet />
            </div>
        </div>
    )
}