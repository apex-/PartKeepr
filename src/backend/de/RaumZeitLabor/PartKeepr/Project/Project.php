<?php
namespace de\RaumZeitLabor\PartKeepr\Project;

use de\RaumZeitLabor\PartKeepr\User\User,
	de\RaumZeitLabor\PartKeepr\Util\Serializable,
	de\RaumZeitLabor\PartKeepr\Util\Deserializable,
	de\RaumZeitLabor\PartKeepr\Util\BaseEntity;

/**
 * Represents a part in the database. The heart of our project. Handle with care!
 * @Entity **/
class Project extends BaseEntity implements Serializable, Deserializable {
	/**
	 * Specifies the name of the project
	 * @Column(type="string")
	 */
	private $name;
	
	/**
	 * Specifies the user this project belongs to
	 * @ManyToOne(targetEntity="de\RaumZeitLabor\PartKeepr\User\User")
	 */
	private $user;
	
	/**
	 * Holds the parts needed for this project
	 * @OneToMany(targetEntity="de\RaumZeitLabor\PartKeepr\Project\ProjectPart",mappedBy="project",cascade={"persist", "remove"})
	 * @var ArrayCollection
	 */
	private $parts;
	
	/**
	 * Holds the description of this project
	 * @Column(type="string",nullable=true)
	 * @var string
	 */
	private $description;
	
	/**
	 * Constructs a new project
	 */
	public function __construct () {
		$this->parts = new \Doctrine\Common\Collections\ArrayCollection();
	}
		
	/**
	 * Sets the user for this project
	 * @param User $user
	 */
	public function setUser (User $user) {
		$this->user = $user;
	}
	
	/**
	 * Gets the user for this project
	 * @return User
	 */
	public function getUser () {
		return $this->user;
	}
	
	/**
	 * Sets the name for this project 
	 * @param string $name
	 */
	public function setName ($name) {
		$this->name = $name;	
	}
	
	/**
	 * Returns the name of this project
	 */
	public function getName () {
		return $this->name;
	}
	
	/**
	 * Sets the description of this project
	 * @param string $description The description to set
	 */
	public function setDescription ($description) {
		$this->description = $description;
	}
	
	/**
	 * Returns the description of this project
	 * @return string The description
	 */
	public function getDescription () {
		return $this->description;
	}
	
	/**
	 * Returns the parts array
	 * @return ArrayCollection An array of ProjectPart objects
	 */
	public function getParts () {
		return $this->parts;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see de\RaumZeitLabor\PartKeepr\Util.Serializable::serialize()
	 */
	public function serialize () {
		return array(
				"id" => $this->getId(),
				"name" => $this->getName(),
				"description" => $this->getDescription(),
				"parts" => $this->serializeChildren($this->getParts()),
		);
	}
	
	/**
	 * Deserializes the project
	 * @param array $parameters The array with the parameters to set
	 */
	public function deserialize (array $parameters) {
		foreach ($parameters as $key => $value) {
			switch ($key) {
				case "name":
					$this->setName($value);
					break;
				case "description":
					$this->setDescription($value);
					break;
				case "parts":
					$this->deserializeChildren($value, $this->getParts(), "de\RaumZeitLabor\PartKeepr\Project\ProjectPart");
					foreach ($this->getParts() as $part) {
						$part->setProject($this);
					}
					break;
			}
		}
	}
	
}