from fastapi import FastAPI, HTTPException, Query, status
from typing import List, Optional
from datetime import datetime

import storage
from models import TaskBase, TaskUpdate, TaskPartialUpdate, TaskResponse

app = FastAPI()

#GET /
@app.get("/")
def read_root():
    return {"message": "Advanced Task API", "version": "2.0"}

#GET /tasks/search
@app.get("/tasks/search", response_model=List[TaskResponse])
def search_tasks(
    keyword: Optional[str] = None,
    priority: Optional[str] = Query(None, pattern="^(low|medium|high)$"),
    date_from: Optional[datetime] = None,
    date_to: Optional[datetime] = None
):
    return storage.search_tasks(keyword, priority, date_from, date_to)

#GET /tasks/stats
@app.get("/tasks/stats")
def get_stats():
    return storage.get_stats_data()

#GET /tasks
@app.get("/tasks", response_model=List[TaskResponse])
def get_tasks(
    completed: Optional[bool] = None,
    priority: Optional[str] = Query(None, pattern="^(low|medium|high)$"),
    limit: Optional[int] = None,
    sort_by: Optional[str] = Query(None, pattern="^(created_at|priority)$")
):
    return storage.get_tasks_filtered(completed, priority, limit, sort_by)

#GET /tasks/{task_id}
@app.get("/tasks/{task_id}", response_model=TaskResponse)
def get_task(task_id: int):
    task = storage.get_task_by_id(task_id)
    if not task:
        raise HTTPException(status_code=status.HTTP_404_NOT_FOUND, detail="Task not found")
    return task

#POST /tasks
@app.post("/tasks", response_model=TaskResponse, status_code=status.HTTP_201_CREATED)
def create_task(task_data: TaskBase):
    if storage.title_exists(task_data.title):
        raise HTTPException(status_code=status.HTTP_400_BAD_REQUEST, detail="Task with this title already exists")
    return storage.create_task(task_data)

#GET /tasks/{task_id}
@app.get("/tasks/{task_id}", response_model=TaskResponse)
def get_task(task_id: int):
    task = storage.get_task_by_id(task_id)
    if not task:
        raise HTTPException(status_code=status.HTTP_404_NOT_FOUND, detail="Task not found")
    return task

#PUT /tasks/{task_id}
@app.put("/tasks/{task_id}", response_model=TaskResponse)
def update_task_full(task_id: int, task_data: TaskUpdate):
    updated_task = storage.update_task_full(task_id, task_data)
    if not updated_task:
        raise HTTPException(status_code=status.HTTP_404_NOT_FOUND, detail="Task not found")
    return updated_task

#PATCH /tasks/{task_id}
@app.patch("/tasks/{task_id}", response_model=TaskResponse)
def update_task_partial(task_id: int, task_data: TaskPartialUpdate):
    updated_task = storage.update_task_partial(task_id, task_data)
    if not updated_task:
        raise HTTPException(status_code=status.HTTP_404_NOT_FOUND, detail="Task not found")
    return updated_task

# PATCH /tasks/{task_id}/complete
@app.patch("/tasks/{task_id}/complete", response_model=TaskResponse)
def complete_task(task_id: int):
    result = storage.complete_task(task_id)
    if result == "not_found":
        raise HTTPException(status_code=status.HTTP_404_NOT_FOUND, detail="Task not found")
    if result == "already_completed":
        raise HTTPException(status_code=status.HTTP_400_BAD_REQUEST, detail="Task is already completed")
    return storage.get_task_by_id(task_id)

# PATCH /tasks/{task_id}/delete
@app.patch("/tasks/{task_id}/delete", response_model=TaskResponse)
def delete_task(task_id: int):
    success = storage.delete_task(task_id)
    if not success:
        raise HTTPException(status_code=status.HTTP_404_NOT_FOUND, detail="Task not found")
    return {"message": f"Task {task_id} deleted successfully"}