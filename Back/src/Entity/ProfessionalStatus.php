<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiProperty;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     collectionOperations={
 *        "get",
 *        "post"={"access_control"="is_granted('ROLE_COMMUNITY_ADMIN')", "access_control_message"="Désolé mais seuls les administrateurs peuvent ajouter un statut professionnel !"}
 *     },
 *     itemOperations={
 *        "get",
 *        "put"={"access_control"="is_granted('ROLE_COMMUNITY_ADMIN')", "access_control_message"="Désolé mais seuls les administrateurs peuvent modifier un statut professionnel !"},
 *        "delete"={"access_control"="is_granted('ROLE_COMMUNITY_ADMIN')", "access_control_message"="Désolé mais mais seuls les administrateurs peuvent supprimer un statut professionnel"}
 *     },
 *     iri="http://schema.org/hasOccupation",
 * )
 * @ORM\Entity(repositoryClass="App\Repository\ProfessionalStatusRepository")
 */
class ProfessionalStatus
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Groups({"user", "AppUserList"})
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank(message="Ce champ ne peut pas être vide")
     * @Assert\Length(
     *      max = 100,
     *      maxMessage = "Le nom du statut professionnel ne doit pas dépasser {{ limit }} caractères"
     * )
     * @ApiProperty(iri="http://schema.org/name")
     */
    private $name;

    /**
     * @Gedmo\Slug(fields={"name"})
     * @ORM\Column(type="string", length=120, nullable=true)
     * @ApiProperty(iri="https://schema.org/URL")
     */
    private $slug;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\AppUser", mappedBy="professionalStatus")
     * @ApiProperty(iri="https://schema.org/Person")
     */
    private $appUsers;

    public function __construct()
    {
        $this->appUsers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return Collection|AppUser[]
     */
    public function getAppUsers(): Collection
    {
        return $this->appUsers;
    }

    public function addAppUser(AppUser $appUser): self
    {
        if (!$this->appUsers->contains($appUser)) {
            $this->appUsers[] = $appUser;
            $appUser->setProfessionalStatus($this);
        }

        return $this;
    }

    public function removeAppUser(AppUser $appUser): self
    {
        if ($this->appUsers->contains($appUser)) {
            $this->appUsers->removeElement($appUser);
            // set the owning side to null (unless already changed)
            if ($appUser->getProfessionalStatus() === $this) {
                $appUser->setProfessionalStatus(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }
}
