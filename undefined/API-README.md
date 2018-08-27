# Readme API/Rest

### Representational State Transfer
It's an architecture who define a set of rules helping to conceive a API and define the way how the server and the client communicate.
In our use case, we will only use principally three of these greats rules, because we always send Json:

 1. **The URI as resource identification .**
 2. **The HTTP verbs as identification of operations: GET/POST/PUT/DELETE.**
 3. **One authentication token (JWT sent inside the header).**


So we don't build a real API RESTful but we will use the good practices and conventions of this one to have a effective API to communicate between the Front and the Back.
Every routes of the API begins by /api.


## Available resources (URI)

|  ||| |
|--|--|--|--|
| Affectation | Announcement | Announcement Type | Bookmark |
| Comment | Difficulty | Locale | Promotion |
| Promotion Link | Role | Specialty | Support |
| Tag | User | Vote | Warning Bookmark |


## Available routes for each resource

| Route name  | Method | Route objective |
|--|--|--|
| /api/resources | GET | Route allowing to receive the list of all items of one resource.  |
| /api/resources/{id} | GET | Route allowing to receive only one item of one resource according to his id  .|
| /api/resources/{id}/{child}/{relation} | GET | Route allowing to receive the list of all items of a specified child resource linked at a precise item (specified by id). |
| /api/resources| POST |Route allowing to persist a new item of one resource (and set his relations with other items) using informations sent in the payload of the request and receive the representation of the item with his new id. |
| /api/resources/{id}| PUT |Route allowing to make modifications on one item of the resource (and set/unset his relations with other items), specified by his id, using informations sent in the payload of the request and receive the representation of the modified item.  |
| /api/resources/{id}| DELETE |Route allowing to suppress one item of the resource (and unset his relations with other items), specified by his id, using informations sent in the payload of the request and receive a message giving the status (success/fail) of the action. |

## Available query strings options (for GET routes)
| Query string  | Title | Query string objective | Default value |
|--|--|--|--|
| ?{field}={value} | Filter | Query string allowing to filter one list of items according at a value in connection with a field | is_active=false
| ?orderField={field} | Sort (field) | Query string allowing to sort one list of items according at a field  | created_at
| ?sortType=[ASC/DESC] | Sort (direction) | Query string allowing to specify a direction of sort for one list of items | DESC
| ?rowsByPage={integer} | Pagination (quantity) | Query string allowing to limit the number of results received for one list of items  | 20 results
| ?pageNumber={integer}| Pagination (offset)| Query string allowing to specify the offset for the first results received for one list of items | first page
| ?displayGroup={serialization group} | Serialization group | Query string allowing to limit the fields serialized to avoid unwanted data and optimize size of response | none

## Available payload options to handle relationship 
| Json key  | Method | Payload option objective |
|--|--|--|
| `add: [ { "id": {relation_id}, "entity": {relation_entity}, "property": {field_relating_to_relation} } ]` | POST/PUT | Option allowing to set a relationship for one item (based on the relation item id, his entity name and the related field.  |
| `remove: [ { "id": {relation_id}, "entity": {relation_entity}, "property": {field_relating_to_relation} } ]` | PUT/DELETE | Option allowing to unset a relationship for one item (based on the relation item id, his entity name and the related field.   |


##  HTTP Response status codes used

| HTTP status label | Code | Use case |
|--|--|--|
| OK| 200 | Standard response for successful HTTP requests. |
| CREATED | 201 | The request has been fulfilled, resulting in the creation of a new resource.   |
| BAD REQUEST | 400 |The server cannot or will not process the request due to an apparent client error. |
| FORBIDDEN | 401 |The request was valid, but the server is refusing action. Probably a problem with the JWT. |
| NOT FOUND | 404 |The requested resource could not be found but may be available in the future. Subsequent requests by the client are permissible. |
| METHOD NON ALLOWED| 405 | A request method is not supported for the requested resource. |
| I'M A TEAPOT| 418 | No coffee here sorry...but you have received a mug, so make it yourself! ;-) |


## And some custom routes too ... (to fulfill specials needs)

| Route name | Method | Description | Special needs |
|--|--|--|--|
| /api/rights/{user_id}/promotion/{promotion_id}| GET | Route allowing to directly receive the roles of one user in one promotion (specified by their ids). | Simplify the handling of affectations for front. |
| /api/filters | GET | Route allowing to directly receive the entire list of every "filters" Resources items availables (supports/difficulties/locales/tags) sorted alphabetically. | Avoid to make 4 separates requests.  |



## What's next ?

We are working on a complete Voter system to allow/forbid access at actions according to Roles and ownership of the logged user ( e.g only ROLE_TEACHER or higher AND owner of a bookmark can edit it).
We will also use the possibility for users to have multiples affectations to let them access different promotions with different access rights (e.g a teacher being ROLE_REFERENT for one promotion and only ROLE_TEACHER for others).