import { useState } from "react"
import { AiFillCloseCircle } from 'react-icons/ai'

const AddTask = ( { tasksDispatch, toggleAddForm } ) => {

    const [task, setTaskName] = useState('')
    const [owner, setClientName] = useState('')
    const [errMessage, setMessage] = useState(false)

    function formSubmit(e) {
        e.preventDefault()
        setMessage(false)
        if( formValidation() ) {
            tasksDispatch({type: 'addTask', task, owner })
            setTaskName('')
            setClientName('')
            toggleAddForm()
        }
    }

    function formValidation() {
        if ( !task ) {
            setMessage('Task name is requierd')
            return false
        } else {
            return true
        }
    }

    return (
        <div className='popup_bg'
             onClick={ (e) => e.target.classList.contains('popup_bg') && toggleAddForm() } >
        <form className='add-task-form' onSubmit={formSubmit} >
            { errMessage && <span className='error_message'>
                {errMessage}
                <AiFillCloseCircle className='clouse'
                                   onClick={ () => setMessage(false) }/>
            </span>
            }
            <div className='form-control'>
                <input type='text' placeholder='Task name' value={task}
                       onChange={ (e) => setTaskName(e.target.value) } />
            </div>
            <div className='form-control'>
                <input type='text' placeholder='Client name' value={owner}
                        onChange={ (e) => setClientName(e.target.value) } />
            </div>
            <input className='btn btn-submit' type='submit' value='Save Task' />
        </form>
        </div>
    );

}

export default AddTask;