<?php

namespace App\Entity;

use App\Repository\ReclamationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ReclamationRepository::class)]
class Reclamation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    /**
     * @ORM\Column(length=255)
     * @Assert\NotBlank(message="Le nom ne peut pas être vide")
     * @Assert\Length(
     *      min=4,
     *      max=255,
     *      minMessage="Le nom doit comporter au moins {{ limit }} caractères",
     *      maxMessage="Le nom ne peut pas dépasser {{ limit }} caractères"
     * )
     */


    #[ORM\Column(length: 255)]
    private ?string $nom = null;
    /**
     * @ORM\Column(length=255)
     * @Assert\NotBlank(message="Le prénom ne peut pas être vide")
     * @Assert\Regex(
     *     pattern="/^[a-zA-ZÀ-ÿ\s]*$/",
     *     message="Le prénom ne peut contenir que des lettres et des espaces"
     * )
     */

    #[ORM\Column(length: 255)]
    private ?string $prenom = null;
    /**
     * @ORM\Column(length=255)
     * @Assert\NotBlank(message="Le nom ne peut pas être vide")
     * @Assert\Length(
     *      min=3,
     *      max=255,
     *      minMessage="Le sujet doit comporter au moins {{ limit }} caractères",
     *      maxMessage="Le sujet ne peut pas dépasser {{ limit }} caractères"
     * )
     */

    #[ORM\Column(length: 255)]
    private ?string $sujet = null;
    /**
     * @ORM\Column(length=255)
     * @Assert\NotBlank(message="La description ne peut pas être vide")
     * @Assert\Length(
     *      min=2,
     *      max=255,
     *      maxMessage="La description ne peut pas dépasser {{ limit }} caractères"
     * )
     */
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $img = null;
    /**
     * @ORM\Column(length=255, nullable=true)
     * @Assert\File(
     *     maxSize = "5M",
     *     mimeTypes = {"image/jpeg", "image/png", "image/gif"},
     *     maxSizeMessage = "La taille maximale autorisée pour l'image est de 5MB",
     *     mimeTypesMessage = "Merci de télécharger une image valide (JPEG, PNG, GIF)"
     * )
     */

    #[ORM\OneToOne(mappedBy: 'id_reclamation', cascade: ['persist', 'remove'])]
    private ?Reponse $reponse = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getSujet(): ?string
    {
        return $this->sujet;
    }

    public function setSujet(string $sujet): static
    {
        $this->sujet = $sujet;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getImg(): ?string
    {
        return $this->img;
    }

    public function setImg(?string $img): static
    {
        $this->img = $img;

        return $this;
    }

    public function getReponse(): ?Reponse
    {
        return $this->reponse;
    }

    public function setReponse(Reponse $reponse): static
    {
        // set the owning side of the relation if necessary
        if ($reponse->getIdReclamation() !== $this) {
            $reponse->setIdReclamation($this);
        }

        $this->reponse = $reponse;

        return $this;
    }

    public function __toString(): string
    {
        return $this->nom ?? ''; // Return the 'nom' property, or an empty string if it's null
    }
}
