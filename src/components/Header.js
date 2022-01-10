import PropTypes from 'prop-types'
import { useState, useEffect } from "react";
import { AiFillPlusSquare } from 'react-icons/ai'

const Header = ( { toggleAddForm, showAdd } ) => {

    const [dt, setDt] = useState(new Date().toLocaleString());

    useEffect(() => {
        let secTimer = setInterval( () => {
            setDt(new Date().toLocaleString())
        },1000)

        return () => clearInterval(secTimer);
    }, []);

    return (
        <header>
            <h1>Today: {dt}</h1>
            <div className='actions'>
                <div className='btn add' onClick={toggleAddForm}>
                    <span>Add </span> <AiFillPlusSquare />
                </div>
            </div>
        </header>
    );

}

Header.propTypes = {
    toggleAddForm: PropTypes.func.isRequired,
}

export default Header;