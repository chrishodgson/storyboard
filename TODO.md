
Phase 1
===
Fixtures
    - Change Data Dummy Data Fixture to add snippet_statuses
    - Change Data Dummy Data Fixture to add projects
    - New Data Lookups Fixture for snippet_statuses [draft|active|archived]

Screens
    - List, Add, Edit, View Stories - add project, favorite 
    - List, Add, Edit, View Snippets - add favorite, snippet_status

Refactoring
    - replace annotations with yaml routes
    - write unit tests

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
