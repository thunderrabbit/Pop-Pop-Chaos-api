<?php
/*
A repository is responsible for the data access logic, communication with a database.

There are two types of repositories: collection-oriented and persistence-oriented repositories.
In this case, we are talking about persistence-oriented repositories,
since these are better suited for processing large amounts of data.

A repository is the source of all the data your application needs and mediates
between the service and the database. A repository improves code maintainability,
testing and readability by separating business logic from data access logic and
provides centrally managed and consistent access rules for a data source.
Each public repository method represents a query. The return values represent
the result set of a query and can be primitive/object or list (array) of them.
Database transactions must be handled on a higher level (service) and not within a repository.

The following Repository class uses the Data Mapper pattern.
https://designpatternsphp.readthedocs.io/en/latest/Structural/DataMapper/README.html
A Data Mapper, is a Data Access Layer that performs bidirectional transfer of
data between a persistent data store (often a relational database) and an in
memory data representation (the domain layer). The goal of the pattern is to
keep the in memory representation and the persistent data store independent of
each other and the data mapper itself.

Generic mappers will handle many different domain entity types,
dedicated mappers will handle one or a few.

The key point of this pattern is, unlike Active Record pattern, the data model
follows Single Responsibility Principle.
*/

namespace App\Domain\Bubble\Repository;

use PDO;

/**
 * Repository.
 */
final class BubblesGetterRepository
{
    /**
     * @var PDO The database connection
     */
    private $connection;

    /**
     * Constructor.
     *
     * @param PDO $connection The database connection
     */
    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    /**
     * Get bubbles for initial screen display
     *
     * Possible future param int Which level of bubbles to get, but maybe each level should be a separate function
     *
     * @return array List of bubbles
     */
    public function getBubbles(): array
    {
        $sql = "SELECT * FROM bubbles";

        $stmnt = $this->connection->query($sql);

        $bubbles = array();
        while($row = $stmnt->fetch()) {
          $bubbles[] = $row;
        }

/*
https://phpdelusions.net/pdo_examples/select
        $data = $pdo->query("SELECT * FROM bubbles")->fetchAll();
        // and somewhere later:
        foreach ($data as $row) {
            echo $row['name']."<br />\n";
        }
*/
        return $bubbles;
    }
}
