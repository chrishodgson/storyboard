
MVP Release 
===

- Screens
    - List, Add, Edit, View Projects

- Data fixture
    - Setup dummy projects and assign to stories

- Testing
    - unit tests

- Refactoring
    - throw createNotFoundException when entity not found in controllers

    
Backlog
===
- Screens
    - Favourite icons in the listings ?

- Schema 
    - project table: story_count ?  
    - language table: snippet_count ?
    - all tables: created_at, updated_at ?

- Doctrine PrePersist & PreUpdate 
    hooks: http://symfony.com/doc/2.8/doctrine/lifecycle_callbacks.html
    - language snippet count ?   
    - created_at, updated_at ?   
    - project story count ?

- Screens
    - List, Add, Edit, View Languages ?
    - All Listing - show created_at ?
    
