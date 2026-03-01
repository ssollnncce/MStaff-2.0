import { BrowserRouter, Routes, Route } from 'react-router-dom'
import MainLayout from "./layouts/MainLayout.tsx";
import Dashboard from "./pages/Dashboard/Dashboard.tsx";
import Login from "./pages/Login.tsx";
import { AuthProvider } from './context/AuthContext.tsx';
import ProtectedRoutes from './components/ProtectedRoutes.tsx';

function App() {
  return (
    <AuthProvider>
      <BrowserRouter>
        <Routes>
          <Route path="/login" element={<Login />} />
          <Route element={<ProtectedRoutes><MainLayout /></ProtectedRoutes>}>
            <Route path='/' element={<Dashboard />} />
          </Route>
        </Routes>
      </BrowserRouter>
    </AuthProvider>
  )
}

export default App