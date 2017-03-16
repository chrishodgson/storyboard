
Phase 1 Release
===
- Screens
    - Controller actions to Add/Remove Favourites 

Phase 2 Release
===
- Schema 
    - project table: story_count  
    - language table: snippet_count 
    - all tables: created_at, updated_at

- Doctrine PrePersist & PreUpdate hooks: http://symfony.com/doc/2.8/doctrine/lifecycle_callbacks.html
    - language snippet count    
    - created_at, updated_at    
    - project story count 

- Screens
    - List, Add, Edit, View Languages 
    - List, Add, Edit, View Projects
    - All Listing - show created_at 
    - Favourite icons in the listings

- Data fixture
    - Setup dummy projects and assign to stories

- Testing
    - unit tests

- Refactoring
    - throw $this->createNotFoundException when entity not found in controllers