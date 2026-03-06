import { BrowserRouter, Routes, Route } from 'react-router-dom'
import MainLayout from "./layouts/MainLayout.tsx";
import Dashboard from "./pages/DashboardPage/Dashboard.tsx";
import Login from "./pages/LoginPage/Login.tsx";
import ProjectManagement from "./pages/ProjectManagement/ProjectManagement.tsx";
import { AuthProvider } from './context/AuthContext.tsx';
import ProtectedRoutes from './components/ProtectedRoutes.tsx';
import GuestRoute from './components/GuestRoute.tsx';
import Logout from './components/Logout.tsx';

function App() {
  return (
    <AuthProvider>
      <BrowserRouter>
        <Routes>
          <Route path="/login" element={<GuestRoute><Login /></GuestRoute>} />
          <Route element={<ProtectedRoutes><MainLayout /></ProtectedRoutes>}>
            <Route path='/' element={<Dashboard />} />
            <Route path='/projects' element={<ProjectManagement />} />
            <Route path='/logout' element={<Logout />} />
          </Route>
        </Routes>
      </BrowserRouter>
    </AuthProvider>
  )
}

export default App