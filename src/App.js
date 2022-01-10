import { useState, useReducer, useEffect } from "react"
import Header from "./components/Header"
import Tasks from "./components/Tasks"
import AddTask from "./components/AddTask"
import bgImage from './images/wallpaper.jpg'

function App() {
    const phpJsonServer = 'https://tasktracker.local.muntz.nl/tasks.php'
    const [showAdd, setShowAdd] = useState(false)
    const [tasksData, setTaskList] = useState([])
    const [tasksList, tasksDispatch] = useReducer(tasksActions, tasksData)

    useEffect( () => {
        const getTasks = async () => {
            const tasksFromServer = await fetchTasks()
            console.log(tasksFromServer)
            setTaskList(tasksFromServer)
        }
        getTasks()

        console.log(tasksData)
    }, [])

    const fetchTasks = async () => {
        const res = await fetch(phpJsonServer)
        const data = await res.json()
        return data
    }

    function startTimer(id) {
        console.log(id)
        console.log('start')
        //trak time frontend
    }

    function stopTimer(id) {
        console.log(id)
        console.log('stop')
        //stop on frontend and get time from sv
    }

    function tasksActions(tasksList, action) {
        switch (action.type) {

            case 'doingTask':
                //send request to sv
                tasksList = tasksList.map( (task) => task.id !== action.id ?
                    { ...task, doing: false } : task
                )
                tasksList = tasksList.map( (task) => task.id === action.id ?
                    { ...task, doing: true } : task
                )
                startTimer(action.id)
                return tasksList

            case 'stopTask':
                //send request to sv
                stopTimer(action.id)
                return tasksList.map( (task) => task.id === action.id ?
                    { ...task, doing: false } : task
                )

            case 'doneTask':
                //send request to sv
                return tasksList.map( (task) => task.id === action.id ?
                    { ...task, doing: false, done: true } : task
                )

            case 'deleteTask':
               /* const res = async () => {
                    await fetch(phpJsonServer,{
                        method: 'DELETE',
                    })
                }*/
                return tasksList.filter( (task) => task.id !== action.id )

            case 'addTask':
                /*const resNewTask = async () => { return await fetch(phpJsonServer,{
                    method: 'POST',
                    headers: {
                        'Constent-type': 'application/json',
                    },
                    body: JSON.stringify(action),
                }) }
                console.log(resNewTask)
                const newTask = await resNewTask.json()
                return [...tasksList, newTask]*/

                const id = Math.floor(Math.random() * 1000) + 1
                const newTask = {
                    id:id,
                    task: action.task,
                    owner: action.owner,
                    doing: false,
                    done: false,
                    time: 0
                }
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