import { AiFillPauseCircle, AiFillPlaySquare,  AiFillCheckSquare, AiFillCloseSquare } from 'react-icons/ai'

const Task = ( { task, tasksDispatch } ) => {

    return (
        <li id='task_id_${task.id}'
            className={`task_list_item ${task.doing && 'doing'}`}>

            <span className='task_title'>{task.task}</span>
            <span className='task_owner'>{task.owner}</span>
            <span className='task_time'>{task.time}</span>

            <div className='task_actions'>
                { task.doing ?
                    <AiFillPauseCircle  onClick={() => tasksDispatch({type: 'stopTask', id: task.id}) } className='stopSvg'/>
                    : <AiFillPlaySquare onClick={() => tasksDispatch({type: 'doingTask', id: task.id}) } />
                }

                <AiFillCheckSquare onClick={() => tasksDispatch({type: 'doneTask', id: task.id}) } />
                <AiFillCloseSquare onClick={() => tasksDispatch({type: 'deleteTask', id: task.id}) } />
            </div>

        </li>
    );

}

export default Task;