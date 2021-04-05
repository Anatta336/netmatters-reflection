<?php declare(strict_types=1);

use Netmatters\Database\DatabaseInterface;
use Netmatters\Contact\Message;
use Netmatters\Contact\MessageStore;
use PHPUnit\Framework\TestCase;

class MessageStoreTest extends TestCase
{
    protected function createStubDatabase(): DatabaseInterface
    {
        $stub = $this->createStub(DatabaseInterface::class);
        $stub->method('fetchResults')->willReturn([]);
        $stub->method('runQuery')->willReturn(true);
        return $stub;
    }

    protected function createStubMessage(
        $hasAnyStoredValues,
        $name,
        $email,
        $phone,
        $isOptIn,
        $message,
        $timeSent
    ): Message
    {
        $stub = $this->createStub(Message::class);
        $stub->method('getHasAnyStoredValues')->willReturn($hasAnyStoredValues);
        $stub->method('getName')->willReturn($name);
        $stub->method('getEmail')->willReturn($email);
        $stub->method('getPhone')->willReturn($phone);
        $stub->method('getIsOptIn')->willReturn($isOptIn);
        $stub->method('getMessage')->willReturn($message);
        $stub->method('getTimeSent')->willReturn($timeSent);
        return $stub;
    }

    public function testInstantiates(): void
    {
        $database = $this->createStubDatabase();
        $store = new MessageStore($database);
        $this->assertInstanceOf(MessageStore::class, $store);
    }

    public function testStoreMessageCallsFetchResultsOnDatabase(): void
    {
        $database = $this->createMock(DatabaseInterface::class);

        // expect to call runQuery once
        $database->expects($this->once())
            ->method('runQuery');

        $message = $this->createStubMessage(true, 'Jane Smith',
            'jane@example.com', '(+44) 01234 555 234', true,
            'I want to improve SEO.', new DateTime('2021-01-02 12:00:34')
        );

        $store = new MessageStore($database);
        $store->storeMessage($message);
    }

    public function testStoreMessageSendsCorrectSqlToDatabase(): void
    {
        $database = $this->createMock(DatabaseInterface::class);
        $database->expects($this->once())
            ->method('runQuery')
            ->with(
                // sql query
                $this->equalTo('INSERT INTO contact_message (name, email, phone, marketing_opt_in, message, time_sent) VALUES (?, ?, ?, ?, ?, ?);'),
            );
        $store = new MessageStore($database);

        $message = $this->createStubMessage(true, 'Jane Smith',
            'jane@example.com', '(+44) 01234 555 234', true,
            'I want to improve SEO.', new DateTime('2021-01-02 12:00:34')
        );
        $store->storeMessage($message);
    }

    public function testStoreMessageSendsCorrectValuesToDatabase(): void
    {
        $database = $this->createMock(DatabaseInterface::class);
        $database->expects($this->once())
            ->method('runQuery')
            ->with(
                // sql query
                $this->anything(),
                // values:
                $this->equalTo('Jane Smith'),             // name
                $this->equalTo('jane@example.com'),       // email
                $this->equalTo('(+44) 01234 555 234'),    // phone
                $this->equalTo(1),                        // isOptIn
                $this->equalTo('I want to improve SEO.'), // message
                $this->equalTo('2021-01-02 12:00:34'),    // timeSent
            );
        $store = new MessageStore($database);

        $message = $this->createStubMessage(true, 'Jane Smith',
            'jane@example.com', '(+44) 01234 555 234', true,
            'I want to improve SEO.', new DateTime('2021-01-02 12:00:34')
        );
        $store->storeMessage($message);
    }

    public function testFetchAllMessages(): void
    {
        $database = $this->createMock(DatabaseInterface::class);
        $database->expects($this->once())
            ->method('fetchResults')
            ->with(
                // sql query
                $this->equalTo('SELECT * FROM contact_message ORDER BY time_sent DESC;')
            );
        $store = new MessageStore($database);
        $store->fetchAllMessages();
    }

}