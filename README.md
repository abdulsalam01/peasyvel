# PeasyVel System
By Abdul Salam

# Table of Contents
1. [Introduction](#introduction)
2. [Technical Stack](#technical-stack)
3. [Installation and Setup](#installation-and-setup)
4. [API Endpoints](#api-endpoints)
5. [API Specifications](#api-specifications)
   - [Daily Records API](#daily-records-api)
   - [Users API](#users-api)
6. [Dashboard Views](#dashboard-views)
7. [Background Jobs](#background-jobs)
8. [Event Listener](#event-listener)
9. [Database Optimization](#database-optimization)
10. [Data Visualization](#data-visualization)

## Introduction
This Laravel project is designed to efficiently handle and manage user data retrieved from an external API. It records and analyzes population statistics and average data based on user gender, offering a comprehensive overview of user demographics.

## Technical Stack
- **PHP Version**: 8.2
- **Framework**: Laravel 11
- **Database**: PostgreSQL is utilized as the primary database for persistent data storage.
- **Caching**: Redis serves as an in-memory database to enhance performance and speed up data retrieval.
- **Background Processing**: The system uses a queue and task scheduler to execute jobs in the background, ensuring efficient data handling and processing.

## Installation and Setup
1. Clone this repository to your local machine.
2. Navigate to the project directory and execute `composer install` to install dependencies.
3. Initialize the database structure by running `php artisan migrate`.
4. Start the application with `php artisan serve`.
5. Once these steps are completed, the application is ready for use.

## API Endpoints
- **User Data**: Access user data at `/api/users`, which provides data pagination limited to 20 entries per page.
- **Daily Records**: Access daily records at `/api/daily_records`. This endpoint also supports query parameters like `?today=true` or `?latest=true` to retrieve the latest or today's data, respectively, with a limit of 20 entries.

## API Specifications

### Daily Records API
- **Endpoint**: `{base_url}/api/daily_records?latest=true`
- **Method**: GET
- **Description**: Retrieves the latest daily records data.
- **Response Example**:
  ```json
  {
    "id": "e6a09025-9d77-47c2-be0a-f9f06fecd51b",
    "date": "2024-03-21 15:02:45",
    "male_count": 7,
    "female_count": 7,
    "male_avg_age": "47.5",
    "female_avg_age": "56.75"
  }
  ```

### Users API
- **Endpoint**: `{base_url}/api/users`
- **Method**: GET
- **Description**: Fetches user data with pagination, limited to 20 entries per request.
- **Response Example**:
  ```json
  {
    "current_page": 1,
    "data": [
      {
        "id": "ae3bf311-8c0a-4ad1-a83a-7edc40dcf4c2",
        "gender": "female",
        "name": "{\"last\": \"Daničić\", \"first\": \"Marina\", \"title\": \"Mrs\"}",
        "location": "{\"city\": \"Lapovo\", \"state\": \"North Banat\", \"street\": {\"name\": \"Zlatanovićev Sokak\", \"number\": 3078}, \"country\": \"Serbia\", \"postcode\": 28419, \"timezone\": {\"offset\": \"-6:00\", \"description\": \"Central Time (US & Canada), Mexico City\"}, \"coordinates\": {\"latitude\": \"25.2061\", \"longitude\": \"-135.8494\"}}",
        "age": 56,
        "created_at": "2024-03-21T15:02:01.000000Z",
        "updated_at": "2024-03-21T15:02:01.000000Z"
      },
      // Additional user data truncated for brevity
    ],
    "first_page_url": "http://localhost:8000/api/users?page=1",
    "from": 1,
    "last_page": 1,
    "last_page_url": "http://localhost:8000/api/users?page=1",
    "links": [
      {
        "url": null,
        "label": "&laquo; Previous",
        "active": false
      },
      {
        "url": "http://localhost:8000/api/users?page=1",
        "label": "1",
        "active": true
      },
      {
        "url": null,
        "label": "Next &raquo;",
        "active": false
      }
    ],
    "next_page_url": null,
    "path": "http://localhost:8000/api/users",
    "per_page": 20,
    "prev_page_url": null,
    "to": 14,
    "total": 14
  }
  ```

These API endpoints provide a structured way to access and interact with the user and daily records data. The responses are structured in JSON format, offering a clear and efficient means of data retrieval.

## Dashboard Views
- **User Dashboard**: View user data at `/view/users`, which offers a comprehensive user interface.
- **Daily Records Dashboard**: View daily records at `/view/daily_records`, which displays aggregated user data.

## Background Jobs
- A scheduled job runs hourly to fetch data from an external service and store it in the users table.
- At the end of each day, the system calculates averages and population data for users, storing the results in the daily_records table.

## Event Listener
- The system includes an event listener that monitors changes in the users table. Any modification triggers a recalculation of the average user age and updates the daily_records table accordingly, with a limit of 20 records per date.

## Database Optimization
- Several fields in the users table, such as gender and created_at, are indexed to optimize query performance.
- The date field in the daily_records table is also indexed for faster query execution.

## Data Visualization
- The application features a JavaScript-generated chart that integrates with the latest or today's data from the daily_records API, offering real-time insights.
- A dynamic list of users is available with search functionality that operates client-side to enhance performance and limit data requests, with pagination set to 20 entries per page.

This system is designed to be robust, efficient, and easy to use, providing a comprehensive solution for managing and analyzing user data.