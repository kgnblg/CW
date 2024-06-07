<?php

namespace App\Controller;

use App\Entity\Photo;
use App\Entity\User;
use App\Service\FileUploader;
use App\Response\ApiResponse;
use App\Exception\ApiException;
use App\Service\S3Uploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserController extends AbstractController
{
    private ValidatorInterface $validator;
    private FileUploader $fileUploader;
    private S3Uploader $s3uploader;

    public function __construct(ValidatorInterface $validator, FileUploader $fileUploader, S3Uploader $s3Uploader)
    {
        $this->validator = $validator;
        $this->fileUploader = $fileUploader;
        $this->s3uploader = $s3Uploader;
    }

    /**
     * @Route("/api/users/register", name="app_user_register", methods={"POST"})
     */
    public function register(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $avatar = $request->files->get('avatar');
        $photos = $request->files->get('photos') ?? [];

        if (count($photos) < 4) {
            throw new ApiException('At least 4 photos are required.', Response::HTTP_BAD_REQUEST);
        }

        $user = new User();
        $user->setFirstName($request->request->get('firstname'));
        $user->setLastName($request->request->get('lastname'));
        $user->setEmail($request->request->get('email'));
        $user->setPassword($request->request->get('password'));

        $errors = $this->validator->validate($user);

        if (count($errors) > 0) {
            $errors = join(',', array_map(fn ($e) => $e->getMessage(), iterator_to_array($errors)));
            throw new ApiException(
                'Request can not be validated; ' . $errors,
                Response::HTTP_BAD_REQUEST
            );
        }

        $user->setPassword($encoder->encodePassword($user, $user->getPassword()));

        $imageConstraints = new File([
            'maxSize'          => '5M',
            'maxSizeMessage'   => 'File is too big',
            'mimeTypes'        => [ 'image/jpeg', 'image/png', 'image/x-ms-bmp', 'image/gif' ],
            'mimeTypesMessage' => 'Only images are allowed to be uploaded' 
        ]);

        if ($avatar) {
            $user->setAvatar($this->fileUploader->upload($avatar, $imageConstraints));
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        foreach ($photos as $photo) {
            $p = new Photo();
            $p->setName($user->getFirstName() . "'s Photo");
            $p->setUrl($this->s3uploader->upload($photo, $imageConstraints));
            $user->addPhoto($p);
            $entityManager->persist($p);
            $entityManager->flush();
        }

        // return successful response
        return new ApiResponse();
    }

    /**
     * @Route("/api/users/me", name="app_user_me", methods={"GET"})
     */
    public function me(): Response
    {
        $user = $this->getUser();

        return $this->json(
            $user,
            Response::HTTP_OK,
            [],
            [
                ObjectNormalizer::CIRCULAR_REFERENCE_HANDLER => fn ($o) => $o->getId(),
                ObjectNormalizer::ATTRIBUTES => [
                    'firstName',
                    'lastName',
                    'fullName',
                    'roles',
                    'email',
                    'active',
                    'avatar',
                    'createdAt',
                    'updatedAt',
                    'photos' => [
                        'name',
                        'url',
                        'createdAt',
                        'updatedAt',
                    ]
                ]
            ]
        );
    }
}
