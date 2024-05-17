<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Employee;
use App\Request\AddEmployeeRequest;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class EmployeeController extends AbstractController
{
    #[Route('/employee', name: 'add_employee', methods: ['POST'])]
    public function addEmployee(
        Request $request,
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator
    ): JsonResponse {
        $addEmployeeRequest = AddEmployeeRequest::fromArray($request->toArray());

        $errors = $validator->validate($addEmployeeRequest);

        if (count($errors) > 0) {
            return new JsonResponse(['errors' => (string) $errors], Response::HTTP_BAD_REQUEST);
        }

        $employee = new Employee($addEmployeeRequest->getName(), $addEmployeeRequest->getSurname());

        $entityManager->persist($employee);
        $entityManager->flush($employee);

        return $this->json(['id' => (string)$employee->getId()], Response::HTTP_CREATED);
    }
}
