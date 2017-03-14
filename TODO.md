
Phase 1
===
- New schema 
    - favorite table: id, entity_type, entity_id
     http://doctrine-orm.readthedocs.io/en/latest/reference/inheritance-mapping.html
    - snippet table: snippet_status_id 
    - snippet_status table: id, title 
    - story table: project_id 
    - project table: id, title 

- Data Fixture: snippet_status [draft|active|archived]

- Data Fixture: favorite [story|snippet]

- List, Add, Edit, View Stories - add project, favorite 

- List, Add, Edit, View Snippets - add favorite, snippet_status

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
