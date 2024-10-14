# Реализованные методы
- /api/user метод: POST - Создание нового пользователя. Принимает следующие параметры в формате JSON: Name(string), LastName(string), Age(integer), UserName(string), Password(string).
- /api/login метод: POST - Аутентификация пользователя и получение JWT токена. Принимает следующие параметры в формате JSON: username(string), password(string).
- /api/users метод: GET - Получение всех пользователей
- /api/user/{id} метод: GET - Получение пользователя по id
- /api/user/{id} метод: DELETE - Удаление пользователя по id
- /api/users/edit/{id} метод: PUT - Изменение данных пользователя. Принимает следующие параметры в формате JSON: Name(string), LastName(string), Age(integer), UserName(string), Password(string).