# Referral Program Backend (PHP & Laravel)

This project is a backend implementation for a referral program built with PHP and Laravel. It manages the creation, updating, and deletion of referral programs, along with handling user accounts, partner associations, and referral tracking.

*Note that these files are part of a broader codebase that belongs to Blu Loyalty. By respect to the company's policy rules and the sensitive nature of the codebase, I am only allowed to publish the files I developed myself.*

*As a result, the files included here may seem out of context or incomplete since they are extracted from a much larger system, and their original directory structure cannot be fully replicated. Therefore, testing this code directly as a standalone project might be challenging. However, the provided files demonstrate the logic and functionality I implemented during my internship.*
## Features : 
1. Referral Program Management:
- Create, update, delete, and view referral programs.
- Associate referral programs with partners.
- Filter and search referral programs based on criteria such as partner, active status, and program name/title.
2. User Account Management:
- Handle user login and account creation for managing referral programs.
3. Admin Interface:
- Admins can filter referral programs by partner, status, or search terms.
- Sort referral programs by creation date (ascending/descending).

## Tools used : 
- Backend : PHP, Laravel Framework
- Database : MySQL
- API : Laravel API Resource for transforming referral program data
- Authentication : Session-based authentication for admins

## API Endpoints : 
- GET /api/referral-programs: Retrieve all referral programs.
- POST /api/referral-programs: Create a new referral program.
- PUT /api/referral-programs/{id}: Update a specific referral program.
- DELETE /api/referral-programs/{id}: Delete a specific referral program.

## My Role :
1. Implemented Referral Program CRUD Operations :
- Developed API endpoints to create, update, view, and delete referral programs.
- Implemented filtering, searching, and sorting logic for admin use.
2. Desgined Database Schema :
- Managed database structure for referral programs and partner associations
3. Wrote Business Logic :
- Created custom repository methods for handling complex queries and logic, including partner and status filtering.
4. Developed API Resources :
- Used Laravel resources to transform referral program data for API responses.

## Installation & Setup 
1. Clone the repository : git clone https://github.com/your-username/referral-program.git
2. Install dependencies : composer install
3. Set up environment : Copy .env.example to .env and configure your database settings.
4. Run migrations : php artisan migrate
5. Start the server : php artisan serve

