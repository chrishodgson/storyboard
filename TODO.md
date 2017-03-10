
Bugs
===

Features
===
- story project (migrations bundle)
    - dropdown with counts on story search 
    - project in story listing, add, new, show
    - trigger to update counts

- language dropdown with counts on snippet search   
    - trigger to update counts
    
- favorite stories / snippets (migrations bundle)
    - favorite table: id, entity_type [story|snippet], entity_id,  

- created_at, updated_at db fields on all tables (migrations bundle)
    - trait for populating tables

Testing
===
- write unit tests

Refactoring
===
- replace annotations with yaml routes