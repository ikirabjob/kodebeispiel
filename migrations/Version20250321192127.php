<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250321192127 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE contact (contact_id UUID NOT NULL, person_id UUID NOT NULL, contact_type SMALLINT NOT NULL, content JSONB DEFAULT NULL, is_primary BOOLEAN NOT NULL, is_active BOOLEAN NOT NULL, is_verified BOOLEAN NOT NULL, status VARCHAR(255) DEFAULT NULL, notes JSONB DEFAULT NULL, legacy_marker JSONB DEFAULT NULL, created_at TIMESTAMP(6) WITH TIME ZONE NOT NULL, updated_at TIMESTAMP(6) WITH TIME ZONE NOT NULL, PRIMARY KEY(contact_id))');
        $this->addSql('CREATE INDEX IDX_4C62E638217BBB47 ON contact (person_id)');
        $this->addSql('COMMENT ON COLUMN contact.contact_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN contact.person_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN contact.created_at IS \'(DC2Type:datetimetz_immutable_with_microseconds)\'');
        $this->addSql('COMMENT ON COLUMN contact.updated_at IS \'(DC2Type:datetimetz_immutable_with_microseconds)\'');
        $this->addSql('CREATE TABLE country (country_id UUID NOT NULL, legacy_marker JSONB DEFAULT NULL, name VARCHAR(255) NOT NULL, alpha2 VARCHAR(255) NOT NULL, alpha3 VARCHAR(255) NOT NULL, created_at TIMESTAMP(6) WITH TIME ZONE NOT NULL, updated_at TIMESTAMP(6) WITH TIME ZONE NOT NULL, PRIMARY KEY(country_id))');
        $this->addSql('COMMENT ON COLUMN country.country_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN country.created_at IS \'(DC2Type:datetimetz_immutable_with_microseconds)\'');
        $this->addSql('COMMENT ON COLUMN country.updated_at IS \'(DC2Type:datetimetz_immutable_with_microseconds)\'');
        $this->addSql('CREATE TABLE login (login_id UUID NOT NULL, person_id UUID NOT NULL, login_type SMALLINT DEFAULT 0 NOT NULL, login_name VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, notes JSONB DEFAULT NULL, legacy_marker JSONB DEFAULT NULL, options JSONB DEFAULT NULL, roles JSONB DEFAULT NULL, created_at TIMESTAMP(6) WITH TIME ZONE NOT NULL, updated_at TIMESTAMP(6) WITH TIME ZONE NOT NULL, PRIMARY KEY(login_id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_AA08CB10217BBB47 ON login (person_id)');
        $this->addSql('COMMENT ON COLUMN login.login_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN login.person_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN login.created_at IS \'(DC2Type:datetimetz_immutable_with_microseconds)\'');
        $this->addSql('COMMENT ON COLUMN login.updated_at IS \'(DC2Type:datetimetz_immutable_with_microseconds)\'');
        $this->addSql('CREATE TABLE person (person_id UUID NOT NULL, parent_person_id UUID DEFAULT NULL, status SMALLINT DEFAULT NULL, profile JSONB DEFAULT NULL, notes JSONB DEFAULT NULL, legacy_marker JSONB DEFAULT NULL, comment TEXT DEFAULT NULL, personal_points DOUBLE PRECISION DEFAULT \'0\' NOT NULL, structure_points DOUBLE PRECISION DEFAULT \'0\' NOT NULL, onboarding_step SMALLINT DEFAULT 0 NOT NULL, admin_invite SMALLINT DEFAULT 0 NOT NULL, input_errors SMALLINT DEFAULT 0 NOT NULL, revise_status SMALLINT DEFAULT 0 NOT NULL, person_type SMALLINT DEFAULT 3 NOT NULL, public_agreement_stamp JSONB DEFAULT NULL, created_at TIMESTAMP(6) WITH TIME ZONE NOT NULL, updated_at TIMESTAMP(6) WITH TIME ZONE NOT NULL, first_name VARCHAR(255) DEFAULT NULL, last_name VARCHAR(255) DEFAULT NULL, middle_name VARCHAR(255) DEFAULT NULL, PRIMARY KEY(person_id))');
        $this->addSql('CREATE INDEX IDX_34DCD176D03094A9 ON person (parent_person_id)');
        $this->addSql('COMMENT ON COLUMN person.person_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN person.parent_person_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN person.created_at IS \'(DC2Type:datetimetz_immutable_with_microseconds)\'');
        $this->addSql('COMMENT ON COLUMN person.updated_at IS \'(DC2Type:datetimetz_immutable_with_microseconds)\'');
        $this->addSql('CREATE TABLE profile (profile_id UUID NOT NULL, person_id UUID NOT NULL, mailing_lang VARCHAR(255) DEFAULT \'ru\', news_letter SMALLINT DEFAULT 1, event_letter SMALLINT DEFAULT 1, confirmation_code SMALLINT DEFAULT 1, security_codes SMALLINT DEFAULT 1, hide_balance SMALLINT DEFAULT 1, hide_contract SMALLINT DEFAULT 1, hide_transaction SMALLINT DEFAULT 1, PRIMARY KEY(profile_id))');
        $this->addSql('CREATE INDEX IDX_8157AA0F217BBB47 ON profile (person_id)');
        $this->addSql('COMMENT ON COLUMN profile.profile_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN profile.person_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE contact ADD CONSTRAINT FK_4C62E638217BBB47 FOREIGN KEY (person_id) REFERENCES person (person_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE login ADD CONSTRAINT FK_AA08CB10217BBB47 FOREIGN KEY (person_id) REFERENCES person (person_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE person ADD CONSTRAINT FK_34DCD176D03094A9 FOREIGN KEY (parent_person_id) REFERENCES person (person_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE profile ADD CONSTRAINT FK_8157AA0F217BBB47 FOREIGN KEY (person_id) REFERENCES person (person_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE contact DROP CONSTRAINT FK_4C62E638217BBB47');
        $this->addSql('ALTER TABLE login DROP CONSTRAINT FK_AA08CB10217BBB47');
        $this->addSql('ALTER TABLE person DROP CONSTRAINT FK_34DCD176D03094A9');
        $this->addSql('ALTER TABLE profile DROP CONSTRAINT FK_8157AA0F217BBB47');
        $this->addSql('DROP TABLE contact');
        $this->addSql('DROP TABLE country');
        $this->addSql('DROP TABLE login');
        $this->addSql('DROP TABLE person');
        $this->addSql('DROP TABLE profile');
    }
}
