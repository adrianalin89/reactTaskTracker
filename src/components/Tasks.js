import Task from "./Task";

const Tasks = ( { tasks, tasksDispatch } ) => {

    return (
        <>
            <h2>Task List</h2>
            {tasks.some(task => task.done === false) ?
                <ul className='tasks_list'>
                    {
                        tasks.map(
                            (task) => (
                                !task.done &&
                                <Task key={task.id} task={task}
                                      tasksDispatch={tasksDispatch}
                                />
                            )
                        )
                    }
                </ul>
                : <p className='no_tasks_row'>No more tasks!</p>
            }

            { tasks.some( task => task.done === true ) &&
                <ul className='done_tasks_list'>
                    {
                        tasks.map(
                            (task) => (
                                task.done &&
                                <Task key={task.id}
                                    task={task}
                                    tasksDispatch={tasksDispatch}
                                />
                            )
                        )
                    }
                </ul>
            }
        </>
    );

}

export default Tasks;