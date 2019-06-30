<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190630202400 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE borrowing CHANGE user_id user_id INT DEFAULT NULL, CHANGE employee_id employee_id INT DEFAULT NULL, CHANGE material_id material_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE material ADD user_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE material ADD CONSTRAINT FK_7CBE75959D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_7CBE75959D86650F ON material (user_id_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE borrowing CHANGE user_id user_id INT NOT NULL, CHANGE employee_id employee_id INT NOT NULL, CHANGE material_id material_id INT NOT NULL');
        $this->addSql('ALTER TABLE material DROP FOREIGN KEY FK_7CBE75959D86650F');
        $this->addSql('DROP INDEX IDX_7CBE75959D86650F ON material');
        $this->addSql('ALTER TABLE material DROP user_id_id');
    }
}
