
Phase 1
===
- New schema 
    - favorite table: id, entity_type [story|snippet], entity_id 
    - snippet table: status_id [draft|active|archived]
    - project table: id, title 
    - story table: project_id 
    - status table: id, title

- List, Add, Edit, View Stories - add project, favorite 

- List, Add, Edit, View Snippets - add favorite, status

- write unit tests

- replace annotations with yaml routes


Phase 2
===
- New schema 
    - project table: story_count  
    - language table: snippet_count 
    - all tables: created_at, updated_at

- Doctrine PrePersist & PreUpdate hooks 
    http://symfony.com/doc/2.8/doctrine/lifecycle_callbacks.html
    - language snippet count    
    - created_at, updated_at    
    - project story count 

- List, Add, Edit, View Languages

- List, Add, Edit, View Projects

- All Listing - add created_at 
