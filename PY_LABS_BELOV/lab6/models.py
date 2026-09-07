from pydantic import BaseModel, Field
from typing import Optional
from datetime import datetime

# Базовая модель, которую присылает клиент
class TaskBase(BaseModel):
    title: str = Field(..., min_length=1, max_length=100)
    description: Optional[str] = None
    priority: str = Field(default="medium", pattern="^(low|medium|high)$")
    deadline: Optional[datetime] = None

# Модель для полного обновления (PUT) , все базовые поля обязательны, кроме Optional
class TaskUpdate(TaskBase):
    pass

# Модель для частичного обновления (PATCH) , все поля опциональны
class TaskPartialUpdate(BaseModel):
    title: Optional[str] = Field(None, min_length=1, max_length=100)
    description: Optional[str] = None
    priority: Optional[str] = Field(None, pattern="^(low|medium|high)$")
    deadline: Optional[datetime] = None

# Модель, которую возвращает API
class TaskResponse(TaskBase):
    id: int
    completed: bool
    created_at: datetime
    completed_at: Optional[datetime] = None











from pydantic import BaseModel, Field
from typing import Optional
from datetime import datetime

# Ваша базовая модель
class TaskBase(BaseModel):
    title: str = Field(..., min_length=1, max_length=100)
    description: Optional[str] = None
    priority: str = Field(default="medium", pattern="^(low|medium|high)$")
    deadline: Optional[datetime] = None

# Модель, которую возвращает API (добавляем id, статус и даты)
class TaskResponse(TaskBase):
    id: int
    completed: bool
    created_at: datetime
    completed_at: Optional[datetime] = None

