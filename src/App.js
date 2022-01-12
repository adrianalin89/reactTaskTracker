import { useState, useReducer, useEffect } from "react"
import Header from "./components/Header"
import Tasks from "./components/Tasks"
import AddTask from "./components/AddTask"
import bgImage from './images/wallpaper.jpg'

function App() {
    const phpJsonServer = 'https://tasktracker.local.muntz.nl/phpServer/api/'
    const [showAdd, setShowAdd] = useState(false)
    const [tasksList, tasksDispatch] = useReducer(tasksActions, [])

    useEffect( () => {
        const getTasks = async () => {
            const tasksFromServer = await fetchTasks()
            tasksDispatch({
                type: 'loadData',
                payload: tasksFromServer
            })
        }
        getTasks()
    }, [])

    async function fetchTasks() {
        try {
            const res = await fetch(phpJsonServer + 'get.php')
            const data = await res.json()
            return data
        } catch (err) {
            console.log(err)
        }
    }

    async function addTaskAPI(payload) {
        try {
            const res = await fetch(phpJsonServer + 'add.php',{
                method: 'POST',
                headers: {
                    'Constent-type': 'application/json',
                },
                body: payload,
            })
            const data = await res.json()
            console.log(data)
            return data
        } catch (err) {
            console.log(err)
            return []
        }
    }

    async function deleteTaskAPI(payload) {
        try {
            const res = await fetch(phpJsonServer + 'delete.php',{
                method: 'DELETE',
                headers: {
                    'Content-type': 'application/json',
                },
                body: payload,
            })
            console.log(res)
        } catch (err) {
            console.log(err)
        }
    }

    function tasksActions(tasksList, action) {
        switch (action.type) {

            case 'loadData':
                tasksList = action.payload
                return tasksList

            case 'doingTask':
                //send request to sv
                tasksList = tasksList.map( (task) => task.id !== action.id ?
                    { ...task, doing: false } : task
                )
                tasksList = tasksList.map( (task) => task.id === action.id ?
                    { ...task, doing: true } : task
                )
               // startTimer(action.id)
                return tasksList

            case 'stopTask':
                //send request to sv
               // stopTimer(action.id)
                return tasksList.map( (task) => task.id === action.id ?
                    { ...task, doing: false } : task
                )

            case 'doneTask':
                //send request to sv
                return tasksList.map( (task) => task.id === action.id ?
                    { ...task, doing: false, done: true } : task
                )

            case 'deleteTask':
                deleteTaskAPI(JSON.stringify({'id' : action.id}))
                return tasksList.filter( (task) => task.id !== action.id )

            case 'addTask':
                const newTask = addTaskAPI(JSON.stringify(action))
                return [...tasksList, newTask]

            default:
                throw new Error();
        }
    }

  return (
    <div className="App"
        style={{backgroundImage: `url(${bgImage})`}}>
        <Header toggleAddForm={() => setShowAdd(!showAdd)} showAdd={showAdd} />
        { showAdd && <AddTask tasksDispatch={tasksDispatch} toggleAddForm={() => setShowAdd(!showAdd)} /> }
        <div className='task_box'>
            { tasksList.length ?
                <Tasks tasks={tasksList} tasksDispatch={tasksDispatch} /> :
                <span className="no_tasks">No Tasks!</span>
            }
        </div>
    </div>
  )
}

export default App