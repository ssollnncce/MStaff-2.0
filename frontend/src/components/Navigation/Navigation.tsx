import navStyle from './Navigation.module.css'
// import api from '../../api/axios.ts'

function Navigation() {

    // async function getUserData() {
    //     const response = await api.get('/user/data')
    //     return response.data;
    // }

    return (
        <div className={navStyle['nav-container']}>
            <div className={navStyle['nav-container__header']}>
                <div className={navStyle['nav-container__header-text']}>
                    <p className='paragraph-title'>Management Staff</p>
                    <p className='note'>Version 2.0</p>
                </div>
                <div className={navStyle["nav-container__header-user"]}>
                    <div className={navStyle["nav-container__header-user-icon"]}>

                    </div>
                    <div>
                        <p className={navStyle["nav-container__header-user-text"]}>

                        </p>
                    </div>
                </div>
            </div>
        </div>
    )
}

export default Navigation